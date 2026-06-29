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
| File        : pembayaran/update.php
|--------------------------------------------------------------------------
| Project     : Sistem Informasi Pembayaran Listrik
|--------------------------------------------------------------------------
| Fungsi :
| Memproses perubahan data pembayaran listrik.
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
| Memastikan request berasal dari form POST
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");

    exit;

}

/*
|--------------------------------------------------------------------------
| Mengambil seluruh data dari form
|--------------------------------------------------------------------------
| trim() digunakan untuk menghapus spasi di awal dan akhir input.
|--------------------------------------------------------------------------
*/

$id = (int) $_POST['id'];

$nama_pelanggan = trim($_POST['nama_pelanggan']);

$nomor_meter = trim($_POST['nomor_meter']);

$bulan = trim($_POST['bulan']);

$tanggal_bayar = $_POST['tanggal_bayar'];

$jumlah_bayar = trim($_POST['jumlah_bayar']);

$status = trim($_POST['status']);

$keterangan = trim($_POST['keterangan']);

$file_lama = $_POST['file_lama'];


/*
|--------------------------------------------------------------------------
| Membersihkan karakter HTML
|--------------------------------------------------------------------------
*/

$nama_pelanggan = htmlspecialchars($nama_pelanggan);

$nomor_meter = htmlspecialchars($nomor_meter);

$bulan = htmlspecialchars($bulan);

$status = htmlspecialchars($status);

$keterangan = htmlspecialchars($keterangan);

/*
|--------------------------------------------------------------------------
| Validasi Input
|--------------------------------------------------------------------------
*/

if (

    empty($nama_pelanggan) ||

    empty($nomor_meter) ||

    empty($bulan) ||

    empty($tanggal_bayar) ||

    empty($jumlah_bayar) ||

    empty($status)

) {

    die("Semua field wajib diisi.");

}

/*
|--------------------------------------------------------------------------
| Validasi Nominal Pembayaran
|--------------------------------------------------------------------------
*/

if (!is_numeric($jumlah_bayar)) {

    die("Jumlah pembayaran harus berupa angka.");

}

/*
|--------------------------------------------------------------------------
| Validasi Status
|--------------------------------------------------------------------------
*/

$status_valid = [

    "Lunas",

    "Belum Lunas"

];

if (!in_array($status, $status_valid)) {

    die("Status tidak valid.");

}
/*
|--------------------------------------------------------------------------
| PROSES UPLOAD FILE
|--------------------------------------------------------------------------
| Jika user mengupload file baru maka:
| 1. Validasi ekstensi
| 2. Validasi ukuran
| 3. Membuat nama file unik
| 4. Upload file baru
| 5. Setelah upload berhasil, hapus file lama
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Daftar ekstensi yang diperbolehkan
|--------------------------------------------------------------------------
*/

$ekstensi_diizinkan = [

    "jpg",

    "jpeg",

    "png",

    "pdf"

];

/*
|--------------------------------------------------------------------------
| Maksimal ukuran file 5 MB
|--------------------------------------------------------------------------
*/

$ukuran_maksimal = 5 * 1024 * 1024;

/*
|--------------------------------------------------------------------------
| Default menggunakan file lama
|--------------------------------------------------------------------------
*/

$nama_file_database = $file_lama;

/*
|--------------------------------------------------------------------------
| Mengecek apakah user memilih file baru
|--------------------------------------------------------------------------
*/

if (

    isset($_FILES['bukti_pembayaran']) &&

    $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK

){

    /*
    |--------------------------------------------------------------------------
    | Mengambil informasi file
    |--------------------------------------------------------------------------
    */

    $nama_asli = $_FILES['bukti_pembayaran']['name'];

    $ukuran_file = $_FILES['bukti_pembayaran']['size'];

    $tmp_file = $_FILES['bukti_pembayaran']['tmp_name'];

    /*
    |--------------------------------------------------------------------------
    | Mengambil ekstensi file
    |--------------------------------------------------------------------------
    */

    $ext = strtolower(

        pathinfo(

            $nama_asli,

            PATHINFO_EXTENSION

        )

    );

    /*
    |--------------------------------------------------------------------------
    | Validasi ekstensi
    |--------------------------------------------------------------------------
    */

    if(!in_array($ext, $ekstensi_diizinkan)){

        die("Format file harus JPG, JPEG, PNG atau PDF.");

    }

    /*
    |--------------------------------------------------------------------------
    | Validasi ukuran file
    |--------------------------------------------------------------------------
    */

    if($ukuran_file > $ukuran_maksimal){

        die("Ukuran file maksimal 5 MB.");

    }

    /*
    |--------------------------------------------------------------------------
    | Membuat nama file baru
    |--------------------------------------------------------------------------
    */

    $nama_file_database =

        "bukti_" .

        date("YmdHis") .

        "_" .

        bin2hex(random_bytes(4)) .

        "." .

        $ext;

    /*
    |--------------------------------------------------------------------------
    | Lokasi penyimpanan file
    |--------------------------------------------------------------------------
    */

    $tujuan_upload =

        "../uploads/" .

        $nama_file_database;

    /*
    |--------------------------------------------------------------------------
    | Upload file baru
    |--------------------------------------------------------------------------
    */

    if(

        move_uploaded_file(

            $tmp_file,

            $tujuan_upload

        )

    ){

        /*
        |--------------------------------------------------------------------------
        | Upload berhasil
        | Baru hapus file lama
        |--------------------------------------------------------------------------
        */

        if(

            !empty($file_lama)

            &&

            file_exists("../uploads/" . $file_lama)

        ){

            unlink("../uploads/" . $file_lama);

        }

    }else{

        die("Upload file gagal.");

    }

}

/*
|--------------------------------------------------------------------------
| UPDATE DATA PEMBAYARAN
|--------------------------------------------------------------------------
| Menggunakan Prepared Statement agar lebih aman terhadap
| SQL Injection.
|--------------------------------------------------------------------------
*/

$sql = "

UPDATE pembayaran SET

    nama_pelanggan = ?,

    nomor_meter = ?,

    bulan = ?,

    tanggal_bayar = ?,

    jumlah_bayar = ?,

    status = ?,

    bukti_pembayaran = ?,

    keterangan = ?

WHERE id = ?

";

/*
|--------------------------------------------------------------------------
| Membuat Prepared Statement
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare($koneksi, $sql);

/*
|--------------------------------------------------------------------------
| Validasi Prepared Statement
|--------------------------------------------------------------------------
*/

if (!$stmt) {

    die("Gagal menyiapkan query : " . mysqli_error($koneksi));

}

/*
|--------------------------------------------------------------------------
| Binding Parameter
|--------------------------------------------------------------------------
|
| s = string
| d = decimal/double
| i = integer
|--------------------------------------------------------------------------
*/

mysqli_stmt_bind_param(

    $stmt,

    "ssssdsssi",

    $nama_pelanggan,

    $nomor_meter,

    $bulan,

    $tanggal_bayar,

    $jumlah_bayar,

    $status,

    $nama_file_database,

    $keterangan,

    $id

);

/*
|--------------------------------------------------------------------------
| Menjalankan Query Update
|--------------------------------------------------------------------------
*/

$berhasil = mysqli_stmt_execute($stmt);

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
| HASIL UPDATE DATA
|--------------------------------------------------------------------------
| Jika proses update berhasil maka kembali ke halaman index
| dengan pesan sukses.
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

    header("Location: index.php?pesan=update");

    exit;

} else {

    /*
    |--------------------------------------------------------------------------
    | Menampilkan pesan error
    |--------------------------------------------------------------------------
    */

    die(

        "Data gagal diperbarui.<br><br>" .

        mysqli_error($koneksi)

    );

}