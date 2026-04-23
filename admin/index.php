<?php
include "../koneksi.php";

$page = $_GET['page'] ?? 'dashboard';
$message = "";
$msg_type = ""; // success or danger

// Handle Actions
if (isset($_POST['add_suara'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_suara']);
    $file = $_FILES['file_suara'];
    
    $target_dir = "../uploads/audio/";
    $file_name = basename($file["name"]);
    $target_file = $target_dir . $file_name;
    $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    $allowed_ext = ['mp3', 'wav', 'ogg'];
    
    if (!in_array($ext, $allowed_ext)) {
        $message = "⚠️ Format file tidak didukung! Gunakan MP3, WAV, atau OGG.";
        $msg_type = "danger";
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $path = "uploads/audio/" . $file_name;
            mysqli_query($conn, "INSERT INTO suara (nama_suara, file_path) VALUES ('$nama', '$path')");
            $message = "✅ Suara berhasil diunggah!";
            $msg_type = "success";
        } else {
            $message = "❌ Gagal mengunggah file.";
            $msg_type = "danger";
        }
    }
}

if (isset($_GET['delete_suara'])) {
    $id = (int)$_GET['delete_suara'];
    $res = mysqli_query($conn, "SELECT file_path FROM suara WHERE id=$id");
    if ($row = mysqli_fetch_assoc($res)) {
        $file = "../" . $row['file_path'];
        if (file_exists($file)) unlink($file);
        mysqli_query($conn, "DELETE FROM suara WHERE id=$id");
        $message = "✅ Suara berhasil dihapus dari pustaka dan penyimpanan.";
        $msg_type = "success";
    }
}

if (isset($_POST['add_jadwal'])) {
    $hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']) . ":00";
    $kegiatan = mysqli_real_escape_string($conn, $_POST['kegiatan']);
    $id_suara = (int)$_POST['id_suara'];
    
    $query = mysqli_query($conn, "INSERT INTO jadwal (hari, waktu, kegiatan, id_suara) VALUES ('$hari', '$waktu', '$kegiatan', '$id_suara')");
    if ($query) {
        $message = "✅ Jadwal berhasil ditambahkan!";
        $msg_type = "success";
    } else {
        $message = "❌ Gagal menyimpan jadwal.";
        $msg_type = "danger";
    }
}

if (isset($_POST['update_jadwal'])) {
    $id = (int)$_POST['id_jadwal'];
    $hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']) . ":00";
    $kegiatan = mysqli_real_escape_string($conn, $_POST['kegiatan']);
    $id_suara = (int)$_POST['id_suara'];
    
    $query = mysqli_query($conn, "UPDATE jadwal SET hari='$hari', waktu='$waktu', kegiatan='$kegiatan', id_suara='$id_suara' WHERE id=$id");
    if ($query) {
        $message = "✅ Jadwal berhasil diperbarui!";
        $msg_type = "success";
    }
}

if (isset($_GET['delete_jadwal'])) {
    $id = (int)$_GET['delete_jadwal'];
    if (mysqli_query($conn, "DELETE FROM jadwal WHERE id=$id")) {
        $message = "✅ Jadwal berhasil dihapus.";
        $msg_type = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Sistem Bel Sekolah</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { align-items: flex-start; padding: 2rem; }
        .admin-container { display: flex; gap: 2rem; width: 100%; max-width: 1200px; margin: 0 auto; }
        .sidebar { width: 250px; background: rgba(255,255,255,0.05); border-radius: 20px; padding: 1.5rem; }
        .content { flex: 1; background: rgba(255,255,255,0.02); border-radius: 20px; padding: 2rem; }
        .nav-link { display: block; padding: 1rem; color: #94a3b8; text-decoration: none; border-radius: 10px; margin-bottom: 0.5rem; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: var(--primary); color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { text-align: left; padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; color: #94a3b8; }
        input, select { width: 100%; padding: 0.8rem; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; width: auto; border-radius: 8px; cursor: pointer; text-decoration: none; }
        .btn-danger { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-warning { background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3); }
        .alert { padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 500; animation: fadeIn 0.5s ease; }
        .alert-success { background: rgba(74, 222, 128, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
        .alert-danger { background: rgba(248, 113, 113, 0.2); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2 style="color: white; margin-bottom: 2rem;">Admin Bel</h2>
            <nav>
                <a href="?page=dashboard" class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="?page=suara" class="nav-link <?= $page == 'suara' ? 'active' : '' ?>">Pustaka Suara</a>
                <a href="?page=jadwal" class="nav-link <?= $page == 'jadwal' ? 'active' : '' ?>">Atur Jadwal</a>
                <a href="../index.php" class="nav-link">Lihat Player</a>
            </nav>
        </aside>

        <main class="content">
            <?php if ($message): ?>
                <div class="alert alert-<?= $msg_type ?>"><?= $message ?></div>
            <?php endif; ?>

            <?php if ($page == 'dashboard'): ?>
                <h1>Dashboard</h1>
                <p>Selamat datang di panel kontrol Sistem Bel Sekolah Otomatis.</p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem;">
                    <div class="status-card">
                        <h3>Total Jadwal</h3>
                        <?php $c = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM jadwal")); ?>
                        <p style="font-size: 2rem; color: var(--accent);"><?= $c['count'] ?></p>
                    </div>
                    <div class="status-card">
                        <h3>Total Suara</h3>
                        <?php $c = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM suara")); ?>
                        <p style="font-size: 2rem; color: var(--accent);"><?= $c['count'] ?></p>
                    </div>
                </div>

            <?php elseif ($page == 'suara'): ?>
                <h1>Pustaka Suara</h1>
                <form action="" method="POST" enctype="multipart/form-data" class="status-card">
                    <h3>Tambah Suara Baru</h3>
                    <div class="form-group">
                        <label>Nama Suara</label>
                        <input type="text" name="nama_suara" required placeholder="Contoh: Bel Masuk">
                    </div>
                    <div class="form-group">
                        <label>File Audio (MP3, WAV, OGG)</label>
                        <input type="file" name="file_suara" accept="audio/*" required>
                    </div>
                    <button type="submit" name="add_suara" class="btn-primary btn-sm">Upload</button>
                </form>

                <table>
                    <thead>
                        <tr><th>Nama Suara</th><th>Preview</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $res = mysqli_query($conn, "SELECT * FROM suara"); while($r = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nama_suara']) ?></td>
                            <td><audio controls style="height: 30px;"><source src="../<?= $r['file_path'] ?>" type="audio/mpeg"></audio></td>
                            <td>
                                <a href="?page=suara&delete_suara=<?= $r['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Hapus file audio ini secara permanen?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php elseif ($page == 'jadwal' || $page == 'edit_jadwal'): ?>
                <?php 
                $edit_data = null;
                if ($page == 'edit_jadwal' && isset($_GET['id'])) {
                    $id = (int)$_GET['id'];
                    $res = mysqli_query($conn, "SELECT * FROM jadwal WHERE id=$id");
                    $edit_data = mysqli_fetch_assoc($res);
                }
                ?>
                <h1><?= $edit_data ? 'Edit Jadwal' : 'Atur Jadwal' ?></h1>
                <form action="?page=jadwal" method="POST" class="status-card">
                    <?php if ($edit_data): ?>
                        <input type="hidden" name="id_jadwal" value="<?= $edit_data['id'] ?>">
                    <?php endif; ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Hari</label>
                            <select name="hari">
                                <?php $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']; 
                                foreach($days as $d): ?>
                                <option <?= ($edit_data && $edit_data['hari'] == $d) ? 'selected' : '' ?>><?= $d ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Waktu</label>
                            <input type="time" name="waktu" required value="<?= $edit_data ? substr($edit_data['waktu'], 0, 5) : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="kegiatan" required placeholder="Contoh: Jam Masuk" value="<?= $edit_data ? htmlspecialchars($edit_data['kegiatan']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Pilih Suara</label>
                        <select name="id_suara">
                            <?php $s = mysqli_query($conn, "SELECT * FROM suara"); while($sr = mysqli_fetch_assoc($s)): ?>
                            <option value="<?= $sr['id'] ?>" <?= ($edit_data && $edit_data['id_suara'] == $sr['id']) ? 'selected' : '' ?>><?= htmlspecialchars($sr['nama_suara']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="<?= $edit_data ? 'update_jadwal' : 'add_jadwal' ?>" class="btn-primary btn-sm">
                        <?= $edit_data ? 'Perbarui Jadwal' : 'Simpan Jadwal' ?>
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="?page=jadwal" class="nav-link" style="display: inline-block; margin-left: 1rem;">Batal</a>
                    <?php endif; ?>
                </form>

                <?php if (!$edit_data): ?>
                <table>
                    <thead>
                        <tr><th>Hari</th><th>Waktu</th><th>Kegiatan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $res = mysqli_query($conn, "SELECT j.*, s.nama_suara FROM jadwal j LEFT JOIN suara s ON j.id_suara=s.id ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), waktu ASC"); while($r = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?= $r['hari'] ?></td>
                            <td><?= substr($r['waktu'], 0, 5) ?></td>
                            <td><?= htmlspecialchars($r['kegiatan']) ?></td>
                            <td>
                                <a href="?page=edit_jadwal&id=<?= $r['id'] ?>" class="btn-sm btn-warning">Edit</a>
                                <a href="?page=jadwal&delete_jadwal=<?= $r['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
