<?php
session_start();
include 'koneksi.php';

// Cegah akses langsung
if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Pastikan hanya hapus milik sendiri
$cek = mysql_query("SELECT * FROM transaksi WHERE id='$id' AND user_id='$user_id'");
if (mysql_num_rows($cek) > 0) {
  mysql_query("DELETE FROM transaksi WHERE id='$id'");
}

header("Location: index.php");
