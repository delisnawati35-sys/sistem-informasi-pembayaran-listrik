<?php

/*
|--------------------------------------------------------------------------
| INFORMASI FILTER
|--------------------------------------------------------------------------
*/

$filterBulan = $_GET['bulan'] ?? 'Semua Bulan';
$filterStatus = $_GET['status'] ?? 'Semua Status';

$filterPeriode = '-';

if(
    !empty($_GET['tanggal_awal']) &&
    !empty($_GET['tanggal_akhir'])
){

    $filterPeriode =
        date(
            'd-m-Y',
            strtotime($_GET['tanggal_awal'])
        )
        .' s/d '.
        date(
            'd-m-Y',
            strtotime($_GET['tanggal_akhir'])
        );

}

/*
|--------------------------------------------------------------------------
| PROTEKSI HALAMAN
|--------------------------------------------------------------------------
*/

include '../config/auth.php';

?>

<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

<div class="container-fluid">

<div class="row">

<?php include '../components/sidebar.php'; ?>

<main class="col-md-10 p-4">

<div class="card shadow">

<div class="card-header bg-danger text-white">

<h4 class="mb-0">

Filter Laporan PDF

</h4>

</div>

<div class="card-body">

<form
action="pdf.php"
method="GET">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Bulan

</label>

<select
name="bulan"
class="form-select">

<option value="">

Semua Bulan

</option>

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

<option value="<?= $b; ?>">

<?= $b; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Status

</label>

<select
name="status"
class="form-select">

<option value="">

Semua Status

</option>

<option value="Lunas">

Lunas

</option>

<option value="Belum Lunas">

Belum Lunas

</option>

</select>

</div>

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">

        Tanggal Awal

    </label>

    <input
        type="date"
        name="tanggal_awal"
        class="form-control">

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">

        Tanggal Akhir

    </label>

    <input
        type="date"
        name="tanggal_akhir"
        class="form-control">

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

    <a
        href="../pembayaran/index.php"
        class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Kembali

    </a>

    <button
        type="submit"
        class="btn btn-danger">

        <i class="bi bi-file-earmark-pdf"></i>

        Generate PDF

    </button>

</div>

</form>

</div>

</div>

</main>

</div>

</div>

<?php include '../components/footer.php'; ?>
<?php include '../components/scripts.php'; ?>

