<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    $q = mysql_query("SELECT * FROM user WHERE username='$username' AND password='$password'");
    if (mysql_num_rows($q) > 0) {
        $data = mysql_fetch_array($q);
        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level'];

if ($data['level'] == 'admin') {
  header("Location: user_admin.php");
} else {
  header("Location: index.php");
}

  } else {
    $error = "Username atau password salah!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f1f1f1;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: white;
      padding: 30px 25px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 300px;
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .login-box input[type="submit"] {
      width: 100%;
      padding: 10px;
      background: #007bff;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 4px;
      cursor: pointer;
    }

    .login-box input[type="submit"]:hover {
      background: #0056b3;
    }

    .error {
      color: red;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Login</h2>

  <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

  <form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" value="Login">
  </form>
</div>

</body>
</html>
