<?php
session_start();
include 'koneksi.php';
require_once("dompdf/dompdf_config.inc.php");
$user_id = $_SESSION['user_id'];

function tanggal_indo($tanggal) {
    $bulan = array(
      1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecah = explode('-', $tanggal);
    return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
  }
  
  function bulan_indo($tanggal) {
    $bulan = array(
      1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecah = explode('-', $tanggal);
    return $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
  }

  $html = '<h2 align="center">Rekap Laporan</h2>';

if (!empty($_GET['bulan']) && !empty($_GET['tahun'])) {
  $bulan = $_GET['bulan'];
  $tahun = $_GET['tahun'];
  $bulan_list = array(
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
  );
  $nama_bulan = $bulan_list[$bulan];
  $html .= "<p>Periode: <strong>$nama_bulan $tahun</strong></p>";
} elseif (!empty($_GET['tgl1']) && !empty($_GET['tgl2'])) {
  $tgl1 = $_GET['tgl1'];
  $tgl2 = $_GET['tgl2'];
  $html .= "<p>Periode: <strong>" . bulan_indo($tgl1) . "</strong> (<strong>" . tanggal_indo($tgl1) . "</strong> s/d <strong>" . tanggal_indo($tgl2) . "</strong>)</p>";
}

$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>Uraian</th>
  <th>Deskripsi</th>
  <th>Jumlah</th>
  <th>Poin</th>
  <th>Subtotal</th>
</tr>';

$no = 1;
$total = 0;
// lalu gunakan: 
$sql = "SELECT t.*, p.nama FROM transaksi t JOIN uraian p ON t.id_produk=p.id WHERE t.user_id = $user_id";
if (isset($_GET['tgl1']) && $_GET['tgl1'] != '') {
  $tgl1 = $_GET['tgl1'];
  $tgl2 = $_GET['tgl2'];
  $sql .= " WHERE tanggal BETWEEN '$tgl1' AND '$tgl2'";
}

$q = mysql_query($sql);
while ($row = mysql_fetch_array($q)) {
  $html .= "<tr>
    <td>$no</td>
    <td>" . tanggal_indo($row['tanggal']) . "</td>
    <td>$row[nama]</td>
    <td>$row[deskripsi]</td>
    <td>$row[jumlah]</td>
    <td>$row[poin]</td>
    <td>$row[subtotal]</td>
  </tr>";
  $total += $row['subtotal'];
  $no++;
}

$html .= "<tr>
  <td colspan='6' align='center'><strong>Total</strong></td>
  <td><strong>$total</strong></td>
</tr>";

$html .= '</table>';

// Cetak PDF
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper("A4", "potrait");
$dompdf->render();
$dompdf->stream("laporan_transaksi.pdf");
?>
