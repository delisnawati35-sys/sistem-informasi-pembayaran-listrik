<?php
/*
|--------------------------------------------------------------------------
| PROTEKSI HALAMAN
|--------------------------------------------------------------------------
*/

include '../config/auth.php';
?>


<?php
/*
|--------------------------------------------------------------------------
| File        : pembayaran/hapus.php
|--------------------------------------------------------------------------
| Project     : Sistem Informasi Pembayaran Listrik
|--------------------------------------------------------------------------
| Fungsi :
| Menghapus data pembayaran beserta file bukti pembayaran.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Memanggil koneksi database
|--------------------------------------------------------------------------
*/
include '../config/koneksi.php';

/*
|--------------------------------------------------------------------------
| Validasi parameter ID
|--------------------------------------------------------------------------
| Pastikan URL membawa parameter id.
| Contoh:
| hapus.php?id=5
|--------------------------------------------------------------------------
*/
if (!isset($_GET['id'])) {

    header("Location: index.php");

    exit;

}

/*
|--------------------------------------------------------------------------
| Mengambil ID dari URL
|--------------------------------------------------------------------------
| Mengubah nilai menjadi integer agar lebih aman.
|--------------------------------------------------------------------------
*/
$id = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Mengambil data pembayaran berdasarkan ID
|--------------------------------------------------------------------------
| Data ini diperlukan untuk mengetahui nama file bukti pembayaran
| yang akan dihapus dari folder uploads.
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(

    $koneksi,

    "SELECT bukti_pembayaran FROM pembayaran WHERE id = ?"

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

/*
|--------------------------------------------------------------------------
| Jika data tidak ditemukan
|--------------------------------------------------------------------------
*/

if (mysqli_num_rows($result) == 0) {

    mysqli_stmt_close($stmt);

    mysqli_close($koneksi);

    header("Location: index.php");

    exit;

}

/*
|--------------------------------------------------------------------------
| Mengambil data menjadi array
|--------------------------------------------------------------------------
*/

$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

/*
|--------------------------------------------------------------------------
| QUERY MENGHAPUS DATA PEMBAYARAN
|--------------------------------------------------------------------------
| Menggunakan Prepared Statement agar lebih aman.
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(

    $koneksi,

    "DELETE FROM pembayaran WHERE id = ?"

);

/*
|--------------------------------------------------------------------------
| Validasi Prepared Statement
|--------------------------------------------------------------------------
*/

if (!$stmt) {

    die(

        "Gagal menyiapkan query : " .

        mysqli_error($koneksi)

    );

}

/*
|--------------------------------------------------------------------------
| Binding Parameter
|--------------------------------------------------------------------------
*/

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $id

);

/*
|--------------------------------------------------------------------------
| Menjalankan Query DELETE
|--------------------------------------------------------------------------
*/

$berhasil = mysqli_stmt_execute($stmt);

/*
|--------------------------------------------------------------------------
| Jika DELETE berhasil,
| baru hapus file bukti pembayaran.
|--------------------------------------------------------------------------
*/

if ($berhasil) {

    if (

        !empty($data['bukti_pembayaran'])

        &&

        file_exists("../uploads/" . $data['bukti_pembayaran'])

    ) {

        unlink("../uploads/" . $data['bukti_pembayaran']);

    }

}

/*
|--------------------------------------------------------------------------
| Menutup Statement
|--------------------------------------------------------------------------
*/

mysqli_stmt_close($stmt);

/*
|--------------------------------------------------------------------------
| Menutup Koneksi Database
|--------------------------------------------------------------------------
*/

mysqli_close($koneksi);

/*
|--------------------------------------------------------------------------
| HASIL PROSES DELETE
|--------------------------------------------------------------------------
| Jika proses penghapusan berhasil maka pengguna akan
| dikembalikan ke halaman index dengan notifikasi sukses.
|
| Jika gagal maka tampilkan pesan error dari MySQL.
|--------------------------------------------------------------------------
*/

if ($berhasil) {

    /*
    |--------------------------------------------------------------------------
    | Redirect ke halaman index
    |--------------------------------------------------------------------------
    */

    header("Location: index.php?pesan=hapus");

    exit;

} else {

    /*
    |--------------------------------------------------------------------------
    | Menampilkan pesan error jika proses gagal
    |--------------------------------------------------------------------------
    */

    die(

        "Data gagal dihapus.<br><br>" .

        mysqli_error($koneksi)

    );

}