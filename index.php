<?php include 'koneksi.php'; ?>
<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
?>
<?php if ($_SESSION['level'] == 'admin'): ?>
  <p><a href="user_admin.php"><strong>⚙️ Kelola User (Admin)</strong></a></p>
<?php endif; ?>


<?php
// Fungsi untuk konversi tanggal ke format Indonesia
function tanggal_indo($tanggal) {
  $bulan = array(
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  );
  $pecah = explode('-', $tanggal);
  return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
}
?>
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
</style>

<p><strong><?php echo $_SESSION['username']; ?></strong> | <a href="logout.php">Logout</a></p>
<a href="tambah.php">
  <button type="button">+ Tambah</button>

</a>
<br><br>
<form method="get" action="" class="filter-form">
  <div><label>Filter Bulan:</label></div>
    <div>
        <select name="bulan">
        <option value="">-- Pilih Bulan --</option>
        <?php
            $bulan_list = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April','05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus','09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
        foreach ($bulan_list as $key => $val) {
            $selected = (@$_GET['bulan'] == $key) ? 'selected' : '';echo "<option value='$key' $selected>$val</option>";
        }?>
        </select>
    </div>
    <div>
        <select name="tahun">
        <option value="">-- Pilih Tahun --</option>
        <?php
        for ($i = date('Y'); $i >= 2020; $i--) {
            $selected = (@$_GET['tahun'] == $i) ? 'selected' : '';echo "<option value='$i' $selected>$i</option>";
        }?>
        </select>
    </div></div>

  <div><input type="submit" value="Filter"></div>

  <div><a href="cetak_pdf.php?<?php echo http_build_query($_GET); ?>" ">
    <button type="button" class="button-secondary">Cetak PDF</button>
  </a></div>
</form>

<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Uraian</th>
    <th>Deskripsi</th>
    <th>Jumlah</th>
    <th>Poin</th>
    <th>Subtotal</th>
    <th>Aksi</th> <!-- Tambah kolom Aksi -->
  </tr>
  <?php
  $no = 1;
  $total = 0;
  $user_id = $_SESSION['user_id'];

  $sql = "SELECT t.*, p.nama_produk FROM transaksi t JOIN produk p ON t.id_produk=p.id WHERE t.user_id = $user_id";

if (!empty($_GET['bulan']) && !empty($_GET['tahun'])) {
  $bulan = $_GET['bulan'];
  $tahun = $_GET['tahun'];
  $sql .= " AND MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun'";
} elseif (!empty($_GET['tgl1']) && !empty($_GET['tgl2'])) {
  $tgl1 = $_GET['tgl1'];
  $tgl2 = $_GET['tgl2'];
  $sql .= " AND tanggal BETWEEN '$tgl1' AND '$tgl2'";
}
  $q = mysql_query($sql);
  while ($row = mysql_fetch_array($q)) {
    echo "<tr>
      <td>$no</td>
      <td>" . tanggal_indo($row['tanggal']) . "</td>
      <td>$row[nama_produk]</td>
      <td>$row[deskripsi]</td>
      <td>$row[jumlah]</td>
      <td>$row[harga]</td>
      <td>$row[subtotal]</td>
  <td><a href=\"hapus.php?id=$row[id]\" onclick=\"return confirm('Yakin ingin menghapus?')\">
      <button style='background:red;color:white;border:none;padding:4px 8px;border-radius:4px;'>Hapus</button>
    </a>
  </td>
    </tr>";
    $total += $row['subtotal'];
    $no++;
  }
  ?>
  <tr>
    <td colspan="6" align="right"><strong>Total</strong></td>
    <td><strong><?php echo $total; ?></strong></td>
    <td  ></td>
  </tr>
</table>
