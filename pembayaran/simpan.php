<?php
/*
|--------------------------------------------------------------------------
| PROTEKSI HALAMAN
|--------------------------------------------------------------------------
*/
include '../config/auth.php';

/*
|--------------------------------------------------------------------------
| KONEKSI DATABASE
|--------------------------------------------------------------------------
*/
include '../config/koneksi.php';

/*
|--------------------------------------------------------------------------
| VALIDASI REQUEST
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: tambah.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA FORM
|--------------------------------------------------------------------------
*/
$nama_pelanggan = trim($_POST['nama_pelanggan']);
$nomor_meter    = trim($_POST['nomor_meter']);
$bulan          = trim($_POST['bulan']);
$tanggal_bayar  = $_POST['tanggal_bayar'];
$jumlah_bayar   = trim($_POST['jumlah_bayar']);
$status         = trim($_POST['status']);
$keterangan     = trim($_POST['keterangan']);

/*
|--------------------------------------------------------------------------
| VALIDASI INPUT
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
    die("Semua data wajib diisi.");
}

if (!is_numeric($jumlah_bayar)) {
    die("Jumlah pembayaran harus berupa angka.");
}

/*
|--------------------------------------------------------------------------
| VALIDASI FILE
|--------------------------------------------------------------------------
*/
if (!isset($_FILES['bukti_pembayaran'])) {
    die("File tidak ditemukan.");
}

if ($_FILES['bukti_pembayaran']['error'] != 0) {
    die("Silakan pilih file bukti pembayaran.");
}

$allowed = ['jpg', 'jpeg', 'png', 'pdf'];

$nama_asli = $_FILES['bukti_pembayaran']['name'];
$tmp       = $_FILES['bukti_pembayaran']['tmp_name'];
$size      = $_FILES['bukti_pembayaran']['size'];

$ext = strtolower(pathinfo($nama_asli, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("Format file harus JPG, JPEG, PNG atau PDF.");
}

if ($size > 5 * 1024 * 1024) {
    die("Ukuran file maksimal 5 MB.");
}

/*
|--------------------------------------------------------------------------
| MEMBUAT NAMA FILE BARU
|--------------------------------------------------------------------------
*/
$nama_file = uniqid('bukti_') . "." . $ext;

$folder = __DIR__ . "/../uploads/";

if (!move_uploaded_file($tmp, $folder . $nama_file)) {
    die("Upload file gagal.");
}
/*
|--------------------------------------------------------------------------
| SIMPAN KE DATABASE
|--------------------------------------------------------------------------
*/
$sql = "INSERT INTO pembayaran
(
    nama_pelanggan,
    nomor_meter,
    bulan,
    tanggal_bayar,
    jumlah_bayar,
    status,
    bukti_pembayaran,
    keterangan
)
VALUES
(
    ?,?,?,?,?,?,?,?
)";

$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssdsss",
    $nama_pelanggan,
    $nomor_meter,
    $bulan,
    $tanggal_bayar,
    $jumlah_bayar,
    $status,
    $nama_file,
    $keterangan
);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);

    header("Location: index.php?pesan=sukses");
    exit;

}

die(mysqli_error($koneksi));