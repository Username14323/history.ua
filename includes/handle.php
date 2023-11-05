<?php
$connection = mysqli_connect('127.0.0.1', 'root', '', 'HistoryTime');

if (!$connection) {
  echo 'Не вдалося підключитися до бази даних!<br>';
  echo mysqli_connect_error();
  exit();
}

$login = $_POST['login'];
$password = $_POST['password'];
$email = $_POST['email'];

// Перевіряємо, чи існує користувач з таким логіном вже в базі даних
$count = mysqli_query($connection, "SELECT * FROM `users` WHERE `login` = '$login'");

if (mysqli_num_rows($count) > 0) {
  echo '<div class="message">Користувач з таким логіном уже зареєстрований!</div>';
  echo '<a href="../siteFiles/register.html">Повернутися до форми реєстрації</a>';
} else {
  // Реєстрація нового користувача
  $result = mysqli_query($connection, "INSERT INTO `users` (`login`, `password`, `email`) VALUES ('$login', '$password', '$email')");
  if ($result) {
    // Реєстрація пройшла успішно, перенаправляємо користувача на сторінку профілю
    header("Location: ../siteFiles/profile.php?login=$login");
    exit();
  } else {
    echo '<div class="message">Помилка при реєстрації.</div>';
  }
}
?>
