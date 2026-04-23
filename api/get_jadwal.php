<?php
include "../koneksi.php";

// Set timezone to match system
date_default_timezone_set("Asia/Jakarta");

function getHariIndo($day) {
    $map = [
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
        'Sunday'    => 'Minggu'
    ];
    return $map[$day] ?? $day;
}

$hari = getHariIndo(date("l"));

$sql = "SELECT j.*, s.file_path 
        FROM jadwal j 
        LEFT JOIN suara s ON j.id_suara = s.id 
        WHERE j.hari = '$hari' AND j.status = 'aktif'
        ORDER BY j.waktu ASC";

$result = mysqli_query($conn, $sql);
$data = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
