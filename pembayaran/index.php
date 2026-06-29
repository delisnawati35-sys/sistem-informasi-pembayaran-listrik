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
| File        : pembayaran/index.php
|--------------------------------------------------------------------------
| Project     : Sistem Data Pembayaran Listrik
|--------------------------------------------------------------------------
| Deskripsi   :
| Halaman utama untuk menampilkan seluruh data pembayaran listrik.
|
| Fitur :
| - Menampilkan seluruh data pembayaran
| - Menampilkan statistik dashboard
| - Tombol tambah data
| - Tombol cetak PDF
| - Fitur pencarian
| - Edit
| - Hapus
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| MEMANGGIL KONEKSI DATABASE
|--------------------------------------------------------------------------
| File koneksi.php digunakan agar halaman dapat
| berkomunikasi dengan database MySQL.
|--------------------------------------------------------------------------
*/

include '../config/koneksi.php';


/*
|--------------------------------------------------------------------------
| MENGAMBIL KEYWORD PENCARIAN
|--------------------------------------------------------------------------
| Jika user mengetik pada form pencarian maka
| keyword akan disimpan pada variabel $keyword.
|--------------------------------------------------------------------------
*/

$keyword = "";

if(isset($_GET['keyword'])){

    $keyword = mysqli_real_escape_string(
        $koneksi,
        $_GET['keyword']
    );

}


/*
|--------------------------------------------------------------------------
| QUERY STATISTIK DASHBOARD
|--------------------------------------------------------------------------
| Total Data Pembayaran
|--------------------------------------------------------------------------
*/

$totalData = mysqli_fetch_assoc(

    mysqli_query(

        $koneksi,

        "SELECT COUNT(*) AS total
         FROM pembayaran"

    )

);


/*
|--------------------------------------------------------------------------
| Total Pembayaran
|--------------------------------------------------------------------------
*/

$totalBayar = mysqli_fetch_assoc(

    mysqli_query(

        $koneksi,

        "SELECT SUM(jumlah_bayar) AS total
         FROM pembayaran"

    )

);


/*
|--------------------------------------------------------------------------
| Total Status Lunas
|--------------------------------------------------------------------------
*/

$totalLunas = mysqli_fetch_assoc(

    mysqli_query(

        $koneksi,

        "SELECT COUNT(*) AS total
         FROM pembayaran
         WHERE status='Lunas'"

    )

);


/*
|--------------------------------------------------------------------------
| Total Status Belum Lunas
|--------------------------------------------------------------------------
*/

$totalBelum = mysqli_fetch_assoc(

    mysqli_query(

        $koneksi,

        "SELECT COUNT(*) AS total
         FROM pembayaran
         WHERE status='Belum Lunas'"

    )

);


/*
|--------------------------------------------------------------------------
| QUERY DATA PEMBAYARAN
|--------------------------------------------------------------------------
| Jika keyword kosong maka tampilkan semua data.
| Jika keyword diisi maka lakukan pencarian.
|--------------------------------------------------------------------------
*/

if($keyword == ""){

    /*
|--------------------------------------------------------------------------
| QUERY DATA PEMBAYARAN
|--------------------------------------------------------------------------
| Query akan berubah sesuai pencarian dan filter status.
|--------------------------------------------------------------------------
*/

/* ===============================
   Mengambil input pencarian
================================ */

$keyword = mysqli_real_escape_string(

    $koneksi,

    $_GET['keyword'] ?? ''

);

$status = mysqli_real_escape_string(

    $koneksi,

    $_GET['status'] ?? ''

);

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
| Mengatur jumlah data yang ditampilkan pada setiap halaman.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Jumlah data per halaman
|--------------------------------------------------------------------------
*/

$batas = 10;

/*
|--------------------------------------------------------------------------
| Halaman aktif
|--------------------------------------------------------------------------
| Jika parameter page tidak ada, maka dianggap halaman pertama.
|--------------------------------------------------------------------------
*/

$halaman = isset($_GET['page'])

    ? (int) $_GET['page']

    : 1;

/*
|--------------------------------------------------------------------------
| Mencegah halaman bernilai kurang dari 1
|--------------------------------------------------------------------------
*/

if($halaman < 1){

    $halaman = 1;

}

/*
|--------------------------------------------------------------------------
| Menghitung OFFSET
|--------------------------------------------------------------------------
*/

$offset = ($halaman - 1) * $batas;

/* ===============================
   Query dasar
================================ */

$sql = "SELECT * FROM pembayaran WHERE 1=1";

/* ===============================
   Filter keyword
================================ */

if($keyword != ""){

    $sql .= "

    AND (

        nama_pelanggan LIKE '%$keyword%'

        OR

        nomor_meter LIKE '%$keyword%'

    )

    ";

}

/* ===============================
   Filter status
================================ */

if($status != ""){

    $sql .= "

    AND status='$status'

    ";

}

/* ===============================
   Urutkan data terbaru
================================ */

$sql .= "

ORDER BY id DESC

";

/* ===============================
   Menjalankan query
================================ */

$query = mysqli_query(

    $koneksi,

    $sql

);
}else{

    $query = mysqli_query(

        $koneksi,

        "SELECT *
         FROM pembayaran

         WHERE

         nama_pelanggan LIKE '%$keyword%'

         OR

         nomor_meter LIKE '%$keyword%'

         OR

         bulan LIKE '%$keyword%'

         ORDER BY id DESC"

    );

}

?>

<?php include '../components/header.php'; ?>

<?php include '../components/navbar.php'; ?>

<div class="container-fluid">

    <div class="row">

        <?php include '../components/sidebar.php'; ?>

        <main class="col-md-10 p-4">

<!--
|--------------------------------------------------------------------------
| JUDUL HALAMAN
|--------------------------------------------------------------------------
| Menampilkan judul halaman beserta breadcrumb.
|--------------------------------------------------------------------------
-->

<div class="d-flex justify-content-between align-items-center mb-4">

    <!--
==============================================================
FORM PENCARIAN & FILTER
==============================================================
Digunakan untuk mencari data berdasarkan nama pelanggan,
nomor meter, dan status pembayaran.
==============================================================
-->

<form method="GET" class="row g-3 mb-4">

    <!-- Kata Kunci -->
    <div class="col-md-5">

        <input
            type="text"
            name="keyword"
            class="form-control"
            placeholder="Cari nama pelanggan atau nomor meter..."
            value="<?= htmlspecialchars($_GET['keyword'] ?? ''); ?>">

    </div>

    <!-- Filter Status -->
    <div class="col-md-3">

        <select
            name="status"
            class="form-select">

            <option value="">Semua Status</option>

            <option
                value="Lunas"
                <?= (($_GET['status'] ?? '') == "Lunas") ? "selected" : ""; ?>>

                Lunas

            </option>

            <option
                value="Belum Lunas"
                <?= (($_GET['status'] ?? '') == "Belum Lunas") ? "selected" : ""; ?>>

                Belum Lunas

            </option>

        </select>

    </div>

    <!-- Tombol Cari -->
    <div class="col-md-2">

        <button
            type="submit"
            class="btn btn-primary w-100">

            <i class="bi bi-search"></i>

            Cari

        </button>

    </div>

    <!-- Tombol Reset -->
    <div class="col-md-2">

        <a
            href="index.php"
            class="btn btn-secondary w-100">

            <i class="bi bi-arrow-clockwise"></i>

            Reset

        </a>

    </div>

</form>

    <div>

        <h3 class="fw-bold">

            <i class="bi bi-lightning-charge-fill text-warning"></i>

            Data Pembayaran Listrik

        </h3>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">

                    Dashboard

                </li>

                <li class="breadcrumb-item active">

                    Data Pembayaran

                </li>

            </ol>

        </nav>

    </div>

</div>

<!--
|--------------------------------------------------------------------------
| PESAN BERHASIL
|--------------------------------------------------------------------------
| Menampilkan notifikasi apabila proses CRUD berhasil.
|--------------------------------------------------------------------------
-->

<?php

/*
|--------------------------------------------------------------------------
| MENAMPILKAN NOTIFIKASI
|--------------------------------------------------------------------------
| Menampilkan pesan sesuai proses CRUD yang dilakukan.
|--------------------------------------------------------------------------
*/

if(isset($_GET['pesan'])){

    if($_GET['pesan']=="sukses"){

?>

<div class="alert alert-success alert-dismissible fade show" role="alert">

    <i class="bi bi-check-circle-fill"></i>

    Data pembayaran berhasil ditambahkan.

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close">
    </button>

</div>

<?php

    } elseif($_GET['pesan']=="update"){

?>

<div class="alert alert-warning alert-dismissible fade show" role="alert">

    <i class="bi bi-pencil-square"></i>

    Data pembayaran berhasil diperbarui.

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close">
    </button>

</div>

<?php

    } elseif($_GET['pesan']=="hapus"){

?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">

    <i class="bi bi-trash-fill"></i>

    Data pembayaran berhasil dihapus.

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close">
    </button>

</div>

<?php

    }

}

?>



<!--
|--------------------------------------------------------------------------
| CARD STATISTIK
|--------------------------------------------------------------------------
| Menampilkan ringkasan data pembayaran.
|--------------------------------------------------------------------------
-->

<div class="row mb-4">

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">

                    Total Data

                </small>

                <h2 class="fw-bold">

                    <?= $totalData['total']; ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">

                    Total Pembayaran

                </small>

                <h5 class="fw-bold text-success">

                    Rp
                    <?= number_format($totalBayar['total'] ?? 0,2,',','.'); ?>

                </h5>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">

                    Status Lunas

                </small>

                <h2 class="text-success">

                    <?= $totalLunas['total']; ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <small class="text-muted">

                    Belum Lunas

                </small>

                <h2 class="text-danger">

                    <?= $totalBelum['total']; ?>

                </h2>

            </div>

        </div>

    </div>

</div>

<!--
|--------------------------------------------------------------------------
| TOMBOL AKSI DAN PENCARIAN
|--------------------------------------------------------------------------
-->

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-2">

                <a
                        href="tambah.php"
                        class="btn btn-primary">

                    <i class="bi bi-plus-circle"></i>

                    Tambah Data

                </a>

                <a href="../report/index.php"
                    class="btn btn-danger">

                        <i class="bi bi-file-earmark-pdf"></i>

                        Cetak PDF

                </a>
            </div>

            <div class="col-md-6">

                <form
                        method="GET"
                        action="">

                    <div class="input-group">

                        <input

                                type="text"

                                name="keyword"

                                class="form-control"

                                placeholder="Cari nama, nomor meter atau bulan..."

                                value="<?= htmlspecialchars($keyword); ?>">

                        <button
                                class="btn btn-primary"
                                type="submit">

                            <i class="bi bi-search"></i>

                            Cari

                        </button>

                        <a
                                href="index.php"
                                class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<!--
|--------------------------------------------------------------------------
| TABEL DATA PEMBAYARAN
|--------------------------------------------------------------------------
| Menampilkan seluruh data pembayaran dari database.
|--------------------------------------------------------------------------
-->

<div class="card shadow-sm">

    <div class="card-header bg-primary text-white">

        <i class="bi bi-table"></i>

        Data Pembayaran Listrik

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th width="60">No</th>

                        <th>Nama Pelanggan</th>

                        <th>Nomor Meter</th>

                        <th>Bulan</th>

                        <th>Tanggal Bayar</th>

                        <th>Jumlah</th>

                        <th>Status</th>

                        <th>Bukti</th>

                        <th width="190">Aksi</th>

                    </tr>

                </thead>

                <tbody>

<?php

/*
|--------------------------------------------------------------------------
| MEMBUAT NOMOR URUT
|--------------------------------------------------------------------------
*/

$no = 1;

/*
|--------------------------------------------------------------------------
| MENAMPILKAN DATA PEMBAYARAN
|--------------------------------------------------------------------------
*/

if(mysqli_num_rows($query) > 0){

    while($data = mysqli_fetch_assoc($query)){

?>

<tr>

    <!-- Nomor -->

    <td>

        <?= $no++; ?>

    </td>

    <!-- Nama -->

    <td>

        <?= htmlspecialchars($data['nama_pelanggan']); ?>

    </td>

    <!-- Nomor Meter -->

    <td>

        <?= htmlspecialchars($data['nomor_meter']); ?>

    </td>

    <!-- Bulan -->

    <td>

        <?= htmlspecialchars($data['bulan']); ?>

    </td>

    <!-- Tanggal -->

    <td>

        <?= date('d-m-Y', strtotime($data['tanggal_bayar'])); ?>

    </td>

    <!-- Jumlah -->

    <td>

        <strong>

            Rp <?= number_format($data['jumlah_bayar'],2,',','.'); ?>

        </strong>

    </td>

    <!-- Status -->

    <td>

<?php

if($data['status']=="Lunas"){

?>

        <span class="badge bg-success">

            Lunas

        </span>

<?php

}else{

?>

        <span class="badge bg-danger">

            Belum Lunas

        </span>

<?php

}

?>

    </td>

    <!-- Bukti Pembayaran -->

    <td>

<?php

/*
|--------------------------------------------------------------------------
| Mengecek apakah file tersedia
|--------------------------------------------------------------------------
*/

$file = "../uploads/" . $data['bukti_pembayaran'];

if(!empty($data['bukti_pembayaran']) && file_exists($file)){

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if(in_array($ext, ['jpg','jpeg','png'])){

?>

        <img
            src="<?= $file; ?>"
            width="70"
            class="img-thumbnail">

<?php

    }elseif($ext=="pdf"){

?>

        <a
            href="<?= $file; ?>"
            target="_blank"
            class="btn btn-danger btn-sm">

            <i class="bi bi-file-earmark-pdf-fill"></i>

            PDF

        </a>

<?php

    }

}else{

?>

        <span class="badge bg-secondary">

            Tidak Ada

        </span>

<?php

}

?>

    </td>

    <!-- Tombol -->

    <td>

        <a
            href="edit.php?id=<?= $data['id']; ?>"
            class="btn btn-warning btn-sm">

            <i class="bi bi-pencil-square"></i>

            Edit

        </a>

        <a
            href="hapus.php?id=<?= $data['id']; ?>"
            class="btn btn-danger btn-sm"

            onclick="return confirm('Yakin ingin menghapus data ini?')">

            <i class="bi bi-trash"></i>

            Hapus

        </a>

    </td>

</tr>

<?php

    }

}else{

?>

<tr>

    <td
        colspan="9"
        class="text-center">

        <div class="py-4">

            <i class="bi bi-database-fill-x fs-1 text-secondary"></i>

            <br><br>

            Belum ada data pembayaran.

        </div>

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

<!--
|--------------------------------------------------------------------------
| PENUTUP HALAMAN
|--------------------------------------------------------------------------
| Menutup tag <main>, <div class="row">, dan <div class="container-fluid">
| kemudian memanggil footer dan JavaScript.
|--------------------------------------------------------------------------
-->

        </main>

    </div>

</div>

<?php
/*
|--------------------------------------------------------------------------
| Memanggil Footer
|--------------------------------------------------------------------------
| Footer berisi informasi copyright aplikasi.
|--------------------------------------------------------------------------
*/
include '../components/footer.php';
?>

<?php
/*
|--------------------------------------------------------------------------
| Memanggil JavaScript
|--------------------------------------------------------------------------
| Berisi Bootstrap JS dan file script.js.
|--------------------------------------------------------------------------
*/
include '../components/scripts.php';
?>