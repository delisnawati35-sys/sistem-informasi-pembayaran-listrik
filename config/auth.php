<?php
/*
|--------------------------------------------------------------------------
| MEMULAI SESSION
|--------------------------------------------------------------------------
| Session digunakan untuk menyimpan status login pengguna.
|--------------------------------------------------------------------------
*/

session_start();

/*
|--------------------------------------------------------------------------
| CEK STATUS LOGIN
|--------------------------------------------------------------------------
| Jika session login belum ada atau bernilai selain true,
| maka pengguna akan diarahkan kembali ke halaman login.
|--------------------------------------------------------------------------
*/

if(
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true
){

    header("Location: ../login/index.php");
    exit;

}

/*
|--------------------------------------------------------------------------
| FILE AUTH SELESAI
|--------------------------------------------------------------------------
| Jika kode berhasil melewati pengecekan di atas,
| berarti pengguna sudah login dan boleh mengakses halaman.
|--------------------------------------------------------------------------
*/
?>