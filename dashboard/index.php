
<?php

include '../config/auth.php';


/*
|--------------------------------------------------------------------------
| MEMANGGIL KONEKSI DATABASE
|--------------------------------------------------------------------------
| Menghubungkan dashboard dengan database MySQL.
|--------------------------------------------------------------------------
*/

include '../config/koneksi.php';

/*
|--------------------------------------------------------------------------
| TOTAL DATA PEMBAYARAN
|--------------------------------------------------------------------------
| Menghitung jumlah seluruh transaksi pembayaran.
|--------------------------------------------------------------------------
*/

$queryTotal = mysqli_query(

    $koneksi,

    "SELECT COUNT(*) AS total FROM pembayaran"

);

$totalData = mysqli_fetch_assoc($queryTotal);

/*
|--------------------------------------------------------------------------
| TOTAL NOMINAL PEMBAYARAN
|--------------------------------------------------------------------------
| Menjumlahkan seluruh pembayaran.
|--------------------------------------------------------------------------
*/

$queryNominal = mysqli_query(

    $koneksi,

    "SELECT SUM(jumlah_bayar) AS total_bayar FROM pembayaran"

);

$totalNominal = mysqli_fetch_assoc($queryNominal);

/*
|--------------------------------------------------------------------------
| TOTAL STATUS LUNAS
|--------------------------------------------------------------------------
*/

$queryLunas = mysqli_query(

    $koneksi,

    "SELECT COUNT(*) AS total_lunas
     FROM pembayaran
     WHERE status='Lunas'"

);

$totalLunas = mysqli_fetch_assoc($queryLunas);

/*
|--------------------------------------------------------------------------
| TOTAL STATUS BELUM LUNAS
|--------------------------------------------------------------------------
*/

$queryBelum = mysqli_query(

    $koneksi,

    "SELECT COUNT(*) AS total_belum
     FROM pembayaran
     WHERE status='Belum Lunas'"

);

$totalBelum = mysqli_fetch_assoc($queryBelum);

/*
|--------------------------------------------------------------------------
| PEMBAYARAN TERBARU
|--------------------------------------------------------------------------
| Mengambil 5 data pembayaran terbaru berdasarkan ID.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PEMBAYARAN TERBARU
|--------------------------------------------------------------------------
| Mengambil 5 data pembayaran terbaru.
|--------------------------------------------------------------------------
*/

$queryTerbaru = mysqli_query(

    $koneksi,

    "SELECT
        nama_pelanggan,
        nomor_meter,
        bulan,
        tanggal_bayar,
        jumlah_bayar,
        status
     FROM pembayaran
     ORDER BY id DESC
     LIMIT 5"

);

?>


<?php include '../components/header.php'; ?>

<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../components/sidebar.php'; ?>

        <main class="col-md-10 p-4">

            <!-- Seluruh isi dashboard di sini -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="fw-bold">
                    Dashboard
                </h3>

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item active">
                            Home
                        </li>

                    </ol>

                </nav>

            </div>

        </div>

        <div class="alert alert-primary">

            <h5 class="mb-1">

                Selamat Datang, Admin 👋

            </h5>

            <small>

                Selamat datang di Sistem Data Pembayaran Listrik.

            </small>

        <!--
==============================================================
KARTU STATISTIK DASHBOARD
==============================================================
Menampilkan ringkasan data pembayaran dari database.
==============================================================
-->

<div class="row">

    <!-- ======================================================
         TOTAL TRANSAKSI
    ======================================================= -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">

                    Total Transaksi

                </h6>

                <h2 class="fw-bold text-primary">

                    <?= $totalData['total']; ?>

                </h2>

                <small class="text-muted">

                    Total data pembayaran

                </small>

            </div>

        </div>

    </div>

    <!-- ======================================================
         TOTAL PEMBAYARAN
    ======================================================= -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">

                    Total Pembayaran

                </h6>

                <!-- ======================================================
     TANGGAL BAYAR
======================================================= -->

                <td>

                    <?= date('d-m-Y', strtotime($data['tanggal_bayar'])); ?>

                </td>

                <h2 class="fw-bold text-success">

                    Rp <?= number_format($totalNominal['total_bayar'] ?? 0, 0, ',', '.'); ?>

                </h2>

                <small class="text-muted">

                    Akumulasi seluruh pembayaran

                </small>

            </div>

        </div>

    </div>

    <!-- ======================================================
         STATUS LUNAS
    ======================================================= -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">

                    Lunas

                </h6>

                <h2 class="fw-bold text-success">

                    <?= $totalLunas['total_lunas']; ?>

                </h2>

                <small class="text-muted">

                    Pembayaran lunas

                </small>

            </div>

        </div>

    </div>

    <!-- ======================================================
         STATUS BELUM LUNAS
    ======================================================= -->

    <div class="col-md-3 mb-3">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">

                    Belum Lunas

                </h6>

                <h2 class="fw-bold text-danger">

                    <?= $totalBelum['total_belum']; ?>

                </h2>

                <small class="text-muted">

                    Menunggu pembayaran

                </small>

            </div>

        </div>

    </div>

</div>
        <div class="card shadow-sm mt-4">

            <div class="card-header bg-primary text-white">

                Pembayaran Terbaru

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-light">

                            <tr>

                                <th>No</th>

                                <th>Nama Pelanggan</th>

                                <th>No. Meter</th>

                                <th>Bulan</th>

                                <th>Tanggal Bayar</th>

                                <th>Jumlah Bayar</th>

                                <th>Status</th>

                            </tr>

                            </thead>

                        <tbody>

                        <?php

                        /*
                        |--------------------------------------------------------------------------
                        | MENAMPILKAN DATA PEMBAYARAN TERBARU
                        |--------------------------------------------------------------------------
                        */

                        $no = 1;

                        if(mysqli_num_rows($queryTerbaru) > 0){

                            while($data = mysqli_fetch_assoc($queryTerbaru)){

                        ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td><?= htmlspecialchars($data['nama_pelanggan']); ?></td>

                            <td><?= htmlspecialchars($data['nomor_meter']); ?></td>

                            <td><?= htmlspecialchars($data['bulan']); ?></td>

                            <!-- Tanggal Bayar -->
                            <td>
                                <?= date('d-m-Y', strtotime($data['tanggal_bayar'])); ?>
                            </td>

                            <!-- Jumlah Bayar -->
                            <td>
                                Rp <?= number_format($data['jumlah_bayar'], 0, ",", "."); ?>
                            </td>

                            <!-- Status -->
                            <td>

                                <?php if($data['status'] == "Lunas"){ ?>

                                    <span class="badge bg-success">
                                        Lunas
                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-danger">
                                        Belum Lunas
                                    </span>

                                <?php } ?>

                            </td>

                        </tr>
                        <?php

                            }

                        }else{

                        ?>

                        <tr>

                            <td colspan="6" class="text-center">

                                Belum ada data pembayaran.

                            </td>

                        </tr>

                        <?php

                        }

?>

</tbody>

                    </table>

                </div>

            </div>

        </div>



                </main>

            </div>

        </div>

        <?php include '../components/footer.php'; ?>
        <?php include '../components/scripts.php'; ?>







