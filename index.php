<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Bel Sekolah Otomatis</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1 id="school-name">Sistem Bel Sekolah</h1>
            <div id="date-display">Memuat tanggal...</div>
        </header>

        <main>
            <div class="clock-card">
                <div id="digital-clock">00:00:00</div>
            </div>

            <div class="status-card">
                <h2>Status Bel</h2>
                <p id="status-text">Sistem siap. Klik tombol di bawah untuk mengaktifkan pemutar otomatis.</p>
                <button id="start-btn" class="btn-primary">Mulai Bel Otomatis</button>
            </div>

            <div class="schedule-card">
                <h2>Jadwal Hari Ini</h2>
                <ul id="schedule-list">
                    <li>Memuat jadwal...</li>
                </ul>
            </div>
        </main>
    </div>
    <script src="assets/player.js"></script>
</body>
</html>
