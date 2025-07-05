<?php
session_start();
include 'koneksi.php';

$id_produk = $_POST['id_produk'];
$jumlah = $_POST['jumlah'];
$deskripsi = $_POST['deskripsi'];
$tanggal = date('Y-m-d');
$user_id = $_SESSION['user_id'];

$data = mysql_fetch_array(mysql_query("SELECT * FROM produk WHERE id='$id_produk'"));
$harga = $data['harga'];
$subtotal = $harga * $jumlah;

mysql_query("INSERT INTO transaksi (id_produk, jumlah, harga, subtotal, tanggal, deskripsi, user_id)
VALUES ('$id_produk', '$jumlah', '$harga', '$subtotal', '$tanggal', '$deskripsi', '$user_id')");

header("Location: index.php");
?>

