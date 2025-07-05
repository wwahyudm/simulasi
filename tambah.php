<?php include 'koneksi.php'; ?>
<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tambah Transaksi</title>
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <style>

  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }
  h2 {
    margin-bottom: 10px;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
  }
  table th, table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
  }
  table th {
    background-color: #f2f2f2;
    color: #333;
  }
  table tr:hover {
    background-color: #f9f9f9;
  }
  input[type="text"],
  input[type="number"],
  input[type="date"],
  textarea,
  select {
    padding: 6px;
    width: 100%;
    margin-bottom: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    box-sizing: border-box;
  }

  input[type="submit"],
  button {
    padding: 8px 14px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  input[type="submit"]:hover,
  button:hover {
    background-color: #0056b3;
  }

  form {
    margin-bottom: 20px;
  }

  .button-secondary {
    background-color: #28a745;
  }

  .button-secondary:hover {
    background-color: #218838;
  }

  .filter-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .filter-form a {
    text-decoration: none;
  }

  .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }


    #hasil {
      border: 1px solid #ccc;
      display: none;
      position: absolute;
      background: #fff;
      max-height: 150px;
      overflow-y: auto;
      width: 200px;
      z-index: 10;
    }
    #hasil div {
      padding: 8px;
      cursor: pointer;
    }
    #hasil div:hover {
      background: #eee;
    }
  </style>
</head>
<body>

<h2>Tambah Transaksi</h2>
<form method="post" action="simpan.php">
  Produk: <br>
  <input type="text" name="nama_produk" id="nama_produk" autocomplete="off">
  <input type="hidden" name="id_produk" id="id_produk">
  <div id="hasil"></div>
  <br>
  Poin: <br>
  <input type="text" name="harga" id="harga" readonly><br>
  Jumlah: <br>
  <input type="number" name="jumlah"><br>
  Deskripsi: <br>
  <textarea name="deskripsi" rows="3" cols="30"></textarea><br><br>
  <input type="submit" value="Simpan">
  <a href="index.php">
  <button type="button">Kembali</button>
</form>

<script>
$(document).ready(function(){
  $("#nama_produk").keyup(function(){
    var keyword = $(this).val();
    if(keyword.length >= 1){
      $.ajax({
        url: "search_produk.php",
        method: "POST",
        data: { keyword: keyword },
        success: function(data){
          $("#hasil").fadeIn().html(data);
        }
      });
    } else {
      $("#hasil").fadeOut();
    }
  });

  $(document).on("click", "#hasil div", function(){
    var nama_produk = $(this).attr("data-nama");
    var id = $(this).attr("data-id");
    var harga = $(this).attr("data-harga");

    $("#nama_produk").val(nama_produk);
    $("#id_produk").val(id);
    $("#harga").val(harga);
    $("#hasil").fadeOut();
  });
});
</script>

</body>
</html>
