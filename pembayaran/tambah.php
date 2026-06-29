<?php
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

            <!-- Judul Halaman -->
            <div class="mb-4">
                <h3 class="fw-bold">Tambah Data Pembayaran</h3>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            Dashboard
                        </li>
                        <li class="breadcrumb-item">
                            Data Pembayaran
                        </li>
                        <li class="breadcrumb-item active">
                            Tambah Data
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Card Form -->
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Input Pembayaran
                </div>

                <div class="card-body">

                    <form action="simpan.php"
                          method="POST"
                          enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">
                                Nama Pelanggan
                            </label>

                            <input
                                type="text"
                                name="nama_pelanggan"
                                class="form-control"
                                placeholder="Masukkan nama pelanggan"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Nomor Meter
                            </label>

                            <input
                                type="text"
                                name="nomor_meter"
                                class="form-control"
                                placeholder="Contoh: 12345678901"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Bulan
                            </label>

                            <select
                                name="bulan"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Bulan --
                                </option>

                                <option>Januari</option>
                                <option>Februari</option>
                                <option>Maret</option>
                                <option>April</option>
                                <option>Mei</option>
                                <option>Juni</option>
                                <option>Juli</option>
                                <option>Agustus</option>
                                <option>September</option>
                                <option>Oktober</option>
                                <option>November</option>
                                <option>Desember</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Tanggal Bayar
                            </label>

                            <input
                                type="date"
                                name="tanggal_bayar"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Jumlah Bayar
                            </label>

                            <input
                                type="number"
                                name="jumlah_bayar"
                                class="form-control"
                                placeholder="Masukkan jumlah pembayaran"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Status --
                                </option>

                                <option value="Lunas">
                                    Lunas
                                </option>

                                <option value="Belum Lunas">
                                    Belum Lunas
                                </option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Upload Bukti Pembayaran
                            </label>

                            <input
                                type="file"
                                name="bukti_pembayaran"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Keterangan
                            </label>

                            <textarea
                                name="keterangan"
                                rows="4"
                                class="form-control"
                                placeholder="Tambahkan keterangan jika diperlukan"></textarea>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-save"></i>
                            Simpan

                        </button>

                        <a href="index.php"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>
                            Kembali

                        </a>

                    </form>

                </div>

            </div>

        </main>

    </div>
</div>

<?php include '../components/footer.php'; ?>
<?php include '../components/scripts.php'; ?>