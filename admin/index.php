<?php
include "../koneksi.php";

$page = $_GET['page'] ?? 'dashboard';

// Handle Actions
if (isset($_POST['add_suara'])) {
    $nama = $_POST['nama_suara'];
    $file = $_FILES['file_suara'];
    $target_dir = "../uploads/audio/";
    $target_file = $target_dir . basename($file["name"]);
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        $path = "uploads/audio/" . basename($file["name"]);
        mysqli_query($conn, "INSERT INTO suara (nama_suara, file_path) VALUES ('$nama', '$path')");
    }
}

if (isset($_POST['add_jadwal'])) {
    $hari = $_POST['hari'];
    $waktu = $_POST['waktu'] . ":00";
    $kegiatan = $_POST['kegiatan'];
    $id_suara = $_POST['id_suara'];
    mysqli_query($conn, "INSERT INTO jadwal (hari, waktu, kegiatan, id_suara) VALUES ('$hari', '$waktu', '$kegiatan', '$id_suara')");
}

if (isset($_GET['delete_jadwal'])) {
    $id = $_GET['delete_jadwal'];
    mysqli_query($conn, "DELETE FROM jadwal WHERE id=$id");
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
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; width: auto; }
        .btn-danger { background: #ef4444; }
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
                        <label>File Audio (MP3)</label>
                        <input type="file" name="file_suara" accept="audio/*" required>
                    </div>
                    <button type="submit" name="add_suara" class="btn-primary btn-sm">Upload</button>
                </form>

                <table>
                    <thead>
                        <tr><th>Nama Suara</th><th>Path</th><th>Pratinjau</th></tr>
                    </thead>
                    <tbody>
                        <?php $res = mysqli_query($conn, "SELECT * FROM suara"); while($r = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?= $r['nama_suara'] ?></td>
                            <td><?= $r['file_path'] ?></td>
                            <td><audio controls style="height: 30px;"><source src="../<?= $r['file_path'] ?>" type="audio/mpeg"></audio></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php elseif ($page == 'jadwal'): ?>
                <h1>Atur Jadwal</h1>
                <form action="" method="POST" class="status-card">
                    <h3>Tambah Jadwal</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Hari</label>
                            <select name="hari">
                                <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option>
                                <option>Jumat</option><option>Sabtu</option><option>Minggu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Waktu</label>
                            <input type="time" name="waktu" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="kegiatan" required placeholder="Contoh: Jam Masuk">
                    </div>
                    <div class="form-group">
                        <label>Pilih Suara</label>
                        <select name="id_suara">
                            <?php $s = mysqli_query($conn, "SELECT * FROM suara"); while($sr = mysqli_fetch_assoc($s)): ?>
                            <option value="<?= $sr['id'] ?>"><?= $sr['nama_suara'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="add_jadwal" class="btn-primary btn-sm">Simpan Jadwal</button>
                </form>

                <table>
                    <thead>
                        <tr><th>Hari</th><th>Waktu</th><th>Kegiatan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $res = mysqli_query($conn, "SELECT j.*, s.nama_suara FROM jadwal j LEFT JOIN suara s ON j.id_suara=s.id ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), waktu ASC"); while($r = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?= $r['hari'] ?></td>
                            <td><?= substr($r['waktu'], 0, 5) ?></td>
                            <td><?= $r['kegiatan'] ?> (<?= $r['nama_suara'] ?>)</td>
                            <td><a href="?page=jadwal&delete_jadwal=<?= $r['id'] ?>" class="btn-primary btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
