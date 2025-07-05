<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['level'] != 'admin') {
  header("Location: index.php");
  exit;
}

// Tambah user
if (isset($_POST['tambah'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $level = $_POST['level'];
  mysql_query("INSERT INTO user (username, password, level) VALUES ('$username', '$password', '$level')");
  header("Location: user_admin.php");
}

// Hapus user
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysql_query("DELETE FROM user WHERE id=$id");
  header("Location: user_admin.php");
}

// Edit user
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $username = $_POST['username'];
  $level = $_POST['level'];
  $sql = "UPDATE user SET username='$username', level='$level'";
  if (!empty($_POST['password'])) {
    $password = md5($_POST['password']);
    $sql .= ", password='$password'";
  }
  $sql .= " WHERE id=$id";
  mysql_query($sql);
  header("Location: user_admin.php");
}

// Ambil data semua user
$q = mysql_query("SELECT * FROM user");

$edit_data = false;
if (isset($_GET['edit'])) {
  $edit_id = $_GET['edit'];
  $edit_query = mysql_query("SELECT * FROM user WHERE id=$edit_id");
  if (mysql_num_rows($edit_query) > 0) {
    $edit_data = mysql_fetch_array($edit_query);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manajemen User - Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
    }

    h2 {
      color: #333;
    }

    .container {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      max-width: 800px;
      margin: auto;
    }

    input, select {
      padding: 8px;
      margin: 6px 0;
      width: 100%;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input[type="submit"] {
      background: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }

    input[type="submit"]:hover {
      background: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    table th {
      background: #007bff;
      color: white;
    }

    a.button {
      padding: 6px 10px;
      background: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      margin-right: 5px;
    }

    a.button:hover {
      background: #218838;
    }

    .delete {
      background: red;
    }

    .delete:hover {
      background: darkred;
    }

    .back {
      margin-bottom: 15px;
    }

    .edit-form {
      background: #f9f9f9;
      padding: 10px;
      margin-bottom: 20px;
      border-left: 5px solid #007bff;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>👤 Manajemen User (Admin) <a href="logout.php">Logout</a></h2>


  <?php if ($edit_data): ?>
    <div class="edit-form">
      <h3>Edit User</h3>
      <form method="post">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <label>Username</label>
        <input type="text" name="username" value="<?= $edit_data['username'] ?>" required>
        <label>Password (kosongkan jika tidak diubah)</label>
        <input type="password" name="password">
        <label>Level</label>
        <select name="level">
          <option value="user" <?= ($edit_data['level'] == 'user' ? 'selected' : '') ?>>User</option>
          <option value="admin" <?= ($edit_data['level'] == 'admin' ? 'selected' : '') ?>>Admin</option>
        </select>
        <input type="submit" name="update" value="Update User">
      </form>
    </div>
  <?php else: ?>
    <h3>Tambah User Baru</h3>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <select name="level">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>
      <input type="submit" name="tambah" value="Tambah User">
    </form>
  <?php endif; ?>

  <h3>📋 Daftar User</h3>
  <table>
    <tr>
      <th>No</th>
      <th>Username</th>
      <th>Level</th>
      <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while ($u = mysql_fetch_array($q)) {
      echo "<tr>
        <td>$no</td>
        <td>$u[username]</td>
        <td>$u[level]</td>
        <td>
          <a href='user_admin.php?edit=$u[id]' class='button'>Edit</a>
          <a href='user_admin.php?hapus=$u[id]' class='button delete' onclick=\"return confirm('Hapus user ini?')\">Hapus</a>
        </td>
      </tr>";
      $no++;
    }
    ?>
  </table>
</div>

</body>
</html>
