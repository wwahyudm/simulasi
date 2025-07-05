<?php
include 'koneksi.php';

$keyword = $_POST['keyword'];
$q = mysql_query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' LIMIT 100");

while ($d = mysql_fetch_array($q)) {
  echo "<div data-id='$d[id]' data-nama='$d[nama_produk]' data-harga='$d[harga]'>
    $d[nama_produk] - $d[harga]
  </div>";
}
?>
