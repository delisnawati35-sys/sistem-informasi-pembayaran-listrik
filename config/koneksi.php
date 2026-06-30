

<?php

$host     = "sql104.infinityfree.com";
$username = "if0_42301072";
$password = "TmfchmrWq5Zu";
$database = "if0_42301072_db_pembayaran_listrik";

$koneksi = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}