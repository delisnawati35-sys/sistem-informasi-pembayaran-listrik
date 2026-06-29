<?php
include '../config/koneksi.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($koneksi, "
    INSERT INTO admin (username, password)
    VALUES ('$username', '$password')
");

header("Location: index.php?pesan=sukses");
?>