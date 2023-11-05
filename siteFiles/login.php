<?php
session_start();
$connection = mysqli_connect('127.0.0.1', 'root', '', 'HistoryTime');

if (!$connection) {
  echo 'Не вдалося підключитися до бази даних!<br>';
  echo mysqli_connect_error();
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = $_POST['login'];
  $password = $_POST['password'];

  $query = mysqli_query($connection, "SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");

  if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);
    $_SESSION['user_id'] = $user['id']; // Сохраняем user_id в сессии
    $_SESSION['login'] = $login; // Также можно сохранить логин, если нужно
    header("Location: profile.php?login=$login");
    exit();
  } else {
    echo '<div class="message">Невірний логін або пароль.</div>';
  }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="../signupstyle.css">
</head>
<body>
  <h1>Вхід</h1>

  <form action="login.php" method="POST">
    <label for="login">Логін</label>
    <input type="text" name="login" id="login" required><br>

    <label for="password">Пароль</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Увійти</button>
  </form>

  <p>Ще не маєте аккаунту? <a href="../siteFiles/register.html">Зареєструватися</a></p>
</body>
</html>
