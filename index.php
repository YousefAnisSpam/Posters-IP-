<?php
session_start();

$accounts = [
    "admin" => ["password" => "admin1", "role" => "admin"],
    "Yousef Anis" => ["password" => "192100029", "role" => "user"],
    "Ahmed Essam" => ["password" => "192100088", "role" => "user"],
    "Yousef Gabr" => ["password" => "192100069", "role" => "user"],
    "Ahmed Amr" => ["password" => "122100001", "role" => "user"],
];

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($accounts[$username]) && $accounts[$username]['password'] === $password) {
      $_SESSION['username'] = $username;
      $_SESSION['role'] = $accounts[$username]['role']; // Store user role

      // Redirect based on role
      if ($_SESSION['role'] === 'admin') {
          header("Location: admin.php"); // Redirect to admin dashboard
      } else {
          header("Location: main_store.php"); // Redirect to user templates
      }
      exit();
  } else {
      $error = "Invalid username or password.";
  }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MoviesStore - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Changa:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Changa', sans-serif;
      background-color: #f5e9d9;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(rgba(245, 233, 217, 0.9), rgba(245, 233, 217, 0.9)),
                  url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect fill="%238b5a2b" width="50" height="50" x="0" y="0"></rect><rect fill="%238b5a2b" width="50" height="50" x="50" y="50"></rect></svg>');
      background-size: 10px 10px;
    }

    .container {
      width: 90%;
      max-width: 400px;
      background-color: white;
      padding: 2rem;
      border-radius: 5px;
      box-shadow: 0 5px 15px rgba(139, 90, 43, 0.2);
      border: 1px solid #d4af37;
    }

    .logo {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #8b5a2b;
      font-size: 2rem;
      font-weight: 700;
    }

    .form-header {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #3a2c20;
      font-size: 1.5rem;
    }

    input {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 1rem;
      border: 1px solid #e0d5c3;
      border-radius: 3px;
      font-family: 'Changa', sans-serif;
      font-size: 1rem;
    }

    input:focus {
      outline: none;
      border-color: #8b5a2b;
      box-shadow: 0 0 0 2px rgba(139, 90, 43, 0.2);
    }

    .login-btn {
      background-color: #8b5a2b;
      color: white;
      border: none;
      padding: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s;
      margin-top: 0.5rem;
    }

    .login-btn:hover {
      background-color: #7a4b24;
    }

    .error {
      color: #c13c3c;
      text-align: center;
      margin-top: 1rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="logo">Vintage-Movie Posters</div>
    <div class="form-header">Login</div>
    <form method="POST">
      <input type="text" name="username" placeholder="Enter your username" required>
      <input type="password" name="password" placeholder="Enter your password" required>
      <input type="submit" class="login-btn" name="login" value="Login">
    </form>
    <?php if (!empty($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
