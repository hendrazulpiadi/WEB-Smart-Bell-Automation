<?php
$host = "localhost";
$user = "root";
$pass = "@Mongsidialok01";
$db   = "db_bel_sekolah";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
