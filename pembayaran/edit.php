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
| File        : pembayaran/edit.php
|--------------------------------------------------------------------------
| Project     : Sistem Informasi Pembayaran Listrik
|--------------------------------------------------------------------------
| Fungsi :
| Menampilkan form edit data pembayaran berdasarkan ID.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Memanggil Koneksi Database
|--------------------------------------------------------------------------
| File koneksi.php digunakan untuk menghubungkan aplikasi
| dengan database MySQL.
|--------------------------------------------------------------------------
*/
include '../config/koneksi.php';

/*
|--------------------------------------------------------------------------
| Validasi Parameter ID
|--------------------------------------------------------------------------
| Pastikan URL membawa parameter id.
| Contoh:
| edit.php?id=5
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
| Mengambil Data Pembayaran
|--------------------------------------------------------------------------
| Menggunakan Prepared Statement agar lebih aman
| terhadap SQL Injection.
|--------------------------------------------------------------------------
*/
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM pembayaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

/*
|--------------------------------------------------------------------------
| Validasi Data
|--------------------------------------------------------------------------
| Jika data tidak ditemukan maka kembali ke halaman utama.
|--------------------------------------------------------------------------
*/
if (mysqli_num_rows($result) == 0) {

    header("Location: index.php");

    exit;

}

/*
|--------------------------------------------------------------------------
| Mengambil Data Menjadi Array
|--------------------------------------------------------------------------
*/
$data = mysqli_fetch_assoc($result);
?>

<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

<div class="container-fluid">

    <div class="row">

        <?php include '../components/sidebar.php'; ?>

        <main class="col-md-10 p-4">

            <!-- ==========================================================
                 JUDUL HALAMAN
            =========================================================== -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h3 class="fw-bold">

                        <i class="bi bi-pencil-square text-warning"></i>

                        Edit Data Pembayaran

                    </h3>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                Dashboard

                            </li>

                            <li class="breadcrumb-item">

                                Data Pembayaran

                            </li>

                            <li class="breadcrumb-item active">

                                Edit

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

            <!-- ==========================================================
                 CARD FORM
            =========================================================== -->

            <div class="card shadow-sm">

                <div class="card-header bg-warning text-dark">

                    <i class="bi bi-pencil-square"></i>

                    Form Edit Pembayaran

                </div>

                <div class="card-body">

                    <form
                        action="update.php"
                        method="POST"
                        enctype="multipart/form-data">

                        <!-- ID -->

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $data['id']; ?>">

                        <!-- File Lama -->

                        <input
                            type="hidden"
                            name="file_lama"
                            value="<?= htmlspecialchars($data['bukti_pembayaran']); ?>">
                        
                        <!-- ==========================================================
     NAMA PELANGGAN
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Nama Pelanggan

    </label>

    <input
        type="text"
        name="nama_pelanggan"
        class="form-control"
        value="<?= htmlspecialchars($data['nama_pelanggan']); ?>"
        required>

</div>

<!-- ==========================================================
     NOMOR METER
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Nomor Meter

    </label>

    <input
        type="text"
        name="nomor_meter"
        class="form-control"
        value="<?= htmlspecialchars($data['nomor_meter']); ?>"
        required>

</div>

<!-- ==========================================================
     BULAN
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Bulan

    </label>

    <select
        name="bulan"
        class="form-select"
        required>

<?php

$bulan = [

    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember"

];

foreach($bulan as $b){

?>

<option
    value="<?= $b; ?>"

    <?= ($data['bulan']==$b) ? "selected" : ""; ?>>

    <?= $b; ?>

</option>

<?php } ?>

    </select>

</div>

<!-- ==========================================================
     TANGGAL BAYAR
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Tanggal Bayar

    </label>

    <input
        type="date"
        name="tanggal_bayar"
        class="form-control"
        value="<?= $data['tanggal_bayar']; ?>"
        required>

</div>

<!-- ==========================================================
     JUMLAH BAYAR
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Jumlah Bayar

    </label>

    <input
        type="number"
        name="jumlah_bayar"
        class="form-control"
        value="<?= $data['jumlah_bayar']; ?>"
        required>

</div>

<!-- ==========================================================
     STATUS
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Status

    </label>

    <select
        name="status"
        class="form-select"
        required>

        <option
            value="Lunas"

            <?= ($data['status']=="Lunas") ? "selected" : ""; ?>>

            Lunas

        </option>

        <option
            value="Belum Lunas"

            <?= ($data['status']=="Belum Lunas") ? "selected" : ""; ?>>

            Belum Lunas

        </option>

    </select>

</div>

<!-- ==========================================================
     KETERANGAN
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Keterangan

    </label>

    <textarea
        name="keterangan"
        rows="4"
        class="form-control"><?= htmlspecialchars($data['keterangan']); ?></textarea>

</div>

<!-- ==========================================================
     BUKTI PEMBAYARAN LAMA
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Bukti Pembayaran Saat Ini

    </label>

    <br>

<?php

/*
|--------------------------------------------------------------------------
| Menampilkan file lama jika tersedia.
|--------------------------------------------------------------------------
*/

if (!empty($data['bukti_pembayaran'])) {

    $file = "../uploads/" . $data['bukti_pembayaran'];

    if (file_exists($file)) {

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {

?>

            <img
                src="<?= $file; ?>"
                width="180"
                class="img-thumbnail">

<?php

        } elseif ($ext == "pdf") {

?>

            <a
                href="<?= $file; ?>"
                target="_blank"
                class="btn btn-danger">

                <i class="bi bi-file-earmark-pdf-fill"></i>

                Lihat PDF

            </a>

<?php

        }

    } else {

?>

        <span class="badge bg-secondary">

            File tidak ditemukan.

        </span>

<?php

    }

} else {

?>

    <span class="badge bg-warning text-dark">

        Belum ada file.

    </span>

<?php

}

?>

</div>

<!-- ==========================================================
     UPLOAD FILE BARU
=========================================================== -->

<div class="mb-3">

    <label class="form-label">

        Ganti Bukti Pembayaran

    </label>

    <input
        type="file"
        name="bukti_pembayaran"
        class="form-control"
        accept=".jpg,.jpeg,.png,.pdf">

    <small class="text-muted">

        Kosongkan jika tidak ingin mengganti file.

    </small>

</div>

<!-- ==========================================================
     TOMBOL AKSI
=========================================================== -->

<div class="d-flex gap-2">

    <button
        type="submit"
        class="btn btn-success">

        <i class="bi bi-save"></i>

        Update Data

    </button>

    <a
        href="index.php"
        class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Kembali

    </a>

</div>

                    </form>

                </div>

            </div>

        </main>

    </div>

</div>

<?php include '../components/footer.php'; ?>

<?php include '../components/scripts.php'; ?>