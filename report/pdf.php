<?php

include '../config/auth.php';
include '../config/koneksi.php';

?>
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
| MEMANGGIL COMPOSER AUTOLOAD
|--------------------------------------------------------------------------
*/

require_once '../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| IMPORT DOMPDF
|--------------------------------------------------------------------------
*/

use Dompdf\Dompdf;
use Dompdf\Options;

/*
|--------------------------------------------------------------------------
| MEMBUAT OBJEK DOMPDF
|--------------------------------------------------------------------------
*/

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

/*
|--------------------------------------------------------------------------
| LOKASI LOGO
|--------------------------------------------------------------------------
*/

$logo = realpath("../assets/images/logo.png");

/*
|--------------------------------------------------------------------------
| FILE SIAP
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| MENGAMBIL DATA PEMBAYARAN
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| MEMBANGUN FILTER QUERY
|--------------------------------------------------------------------------
*/

$where = [];

if(
    isset($_GET['bulan']) &&
    $_GET['bulan'] != ""
){

    $bulan = mysqli_real_escape_string(
        $koneksi,
        $_GET['bulan']
    );

    $where[] = "bulan = '$bulan'";
}

if(
    isset($_GET['status']) &&
    $_GET['status'] != ""
){

    $status = mysqli_real_escape_string(
        $koneksi,
        $_GET['status']
    );

    $where[] = "status = '$status'";
}

if(
    !empty($_GET['tanggal_awal']) &&
    !empty($_GET['tanggal_akhir'])
){

    $tanggalAwal = mysqli_real_escape_string(
        $koneksi,
        $_GET['tanggal_awal']
    );

    $tanggalAkhir = mysqli_real_escape_string(
        $koneksi,
        $_GET['tanggal_akhir']
    );

    $where[] = "
        tanggal_bayar
        BETWEEN '$tanggalAwal'
        AND '$tanggalAkhir'
    ";
}

/*
|--------------------------------------------------------------------------
| MEMBANGUN SQL
|--------------------------------------------------------------------------
*/

$sql = "
SELECT *
FROM pembayaran
";

if(count($where) > 0){

    $sql .= "
    WHERE
    ".implode(" AND ", $where);

}

$sql .= "
ORDER BY id DESC
";

/*
|--------------------------------------------------------------------------
| MENJALANKAN QUERY
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $koneksi,
    $sql
);
/*
|--------------------------------------------------------------------------
| MENGHITUNG TOTAL
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| STATISTIK LAPORAN
|--------------------------------------------------------------------------
*/

$totalTransaksi = mysqli_num_rows($query);

$totalPembayaran = 0;

$totalLunas = 0;

$totalBelum = 0;

$html = '

<table width="100%" style="margin-bottom:15px;">

<tr>

<td width="18%" align="center">

<img src="'.$logo.'" width="70">

</td>

<td align="center">

<h1
style="
margin:0;
color:#0d6efd;
font-family:Arial;
">

SISTEM PEMBAYARAN LISTRIK

</h1>

<h3
style="
margin:5px 0;
">

LAPORAN DATA PEMBAYARAN

</h3>

</td>

</tr>

</table>

<hr>

<table
width="100%"
style="font-size:12px;">

<tr>

<td>

Tanggal Cetak :
'.date('d-m-Y').'

</td>

<td align="right">

Dicetak Oleh :
'.$_SESSION['username'].'



<br>

<table
width="100%"
style="
font-size:12px;
margin-bottom:15px;
">

<tr>

<td width="20%">

Bulan

</td>

<td>

: '.$filterBulan.'

</td>

</tr>

<tr>

<td>

Status

</td>

<td>

: '.$filterStatus.'

</td>

</tr>

<tr>

<td>

Periode

</td>

<td>

: '.$filterPeriode.'

</td>

</tr>

</table>

</td>

</tr>

</table>

<br>

<table
width="100%"
border="1"
cellpadding="8"
style="
border-collapse:collapse;
">

<tr
style="
background:#f2f2f2;
">

<th>Total Transaksi</th>

<th>Lunas</th>

<th>Belum Lunas</th>

<th>Total Pembayaran</th>

</tr>

<tr>

<td align="center">'.$totalTransaksi.'</td>

<td align="center">'.$totalLunas.'</td>

<td align="center">'.$totalBelum.'</td>

<td align="right">

Rp '.number_format(
$totalPembayaran,
0,
',',
'.'
).'

</td>

</tr>

</table>

<br><br>

<div
style="
text-align:right;
">

Bandung,
'.date('d F Y').'

<br><br>

Administrator

<br><br><br>

_____________________

</div>

';
/*
|--------------------------------------------------------------------------
| MEMBUAT FILE PDF
|--------------------------------------------------------------------------
*/

$dompdf->loadHtml($html);

/*
|--------------------------------------------------------------------------
| UKURAN KERTAS
|--------------------------------------------------------------------------
*/

$dompdf->setPaper('A4', 'portrait');

/*
|--------------------------------------------------------------------------
| RENDER PDF
|--------------------------------------------------------------------------
*/

$dompdf->render();

/*
|--------------------------------------------------------------------------
| TAMPILKAN PDF
|--------------------------------------------------------------------------
*/

$dompdf->stream(
    "Laporan_Pembayaran_Listrik.pdf",
    [
        "Attachment" => false
    ]
);
?>


