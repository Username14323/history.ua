<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="../cssfiles/signupstyle.css" />
  <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
  <link rel="manifest" href="../site.webmanifest">
  <?php include "../includes/fonts.php" ?>
  <title>Login</title>
</head>
<body>
  <h2 style="color: white">Login Form</h2>

  <form action="login.php" method="POST">
    <input type="text" name="login" placeholder="Логін" required />
    <input type="password" name="password" placeholder="Пароль" required />
    <input type="email" name="email" placeholder="Email" required />
    <button type="submit">Увійти</button>
    <p class="register-link">У вас ще немає акаунту? <a href="/siteFiles/register.html">Зареєструватися</a></p>
  </form>
</body>
</html>
