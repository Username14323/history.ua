<?php
session_start();
require "../config/config.php";

$connection = mysqli_connect('127.0.0.1', 'root', '', 'HistoryTime');

if (!$connection) {
  echo 'Не вдалося підключитися до бази даних!<br>';
  echo mysqli_connect_error();
  exit();
}

if (!isset($_SESSION['user_id'])) {
  header("Location: ../siteFiles/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Получаем данные из формы
  $author = $_POST['author'];
  $city = $_POST['city'];
  $country = $_POST['country'];
  $title = $_POST['title'];
  $text = $_POST['text'];
  $category = $_POST['category'];

  // Проверяем, загружено ли изображение
  if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
    
    $extensions= array("jpeg","jpg","png");

    if(in_array($file_ext,$extensions)=== false){
      echo "Extension not allowed, please choose a JPEG or PNG file.";
      exit;
    }

    // Перемещаем файл в директорию 
    move_uploaded_file($file_tmp, "../static/images/" . $file_name);

    $image_path = $file_name;
  } else {
    $image_path = "";
  }

  // Добавляем историю в базу данных
  $sql = "INSERT INTO `articles` (`author`, `user_id`, `city`, `country`, `title`, `text`, `categorie_id`, `image`) VALUES ('$author', '$user_id', '$city', '$country', '$title', '$text', '$category', '$image_path')";

  if (mysqli_query($connection, $sql)) {
    echo "История добавлена";
  } else {
    echo "Ошибка: " . mysqli_error($connection);
  }

  mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ukrainian.ua</title>
    <link rel="stylesheet" type="text/css" href="../cssfiles/questionnaire.css">
    <?php include "../includes/fonts.php"?>
</head>
<body>
<div class="name-form" style="padding: 30px; text-align: center;">
  <h1 style="font-size: 64px; color: white; text-shadow: 2px 2px 4px #000000;">Форма для заповнення</h1>
</div>

<div class="gen">
  <h2 style="text-align: center; color: #f2f2f2; font-size: 48px; text-shadow: 2px 2px 4px #000000;">
    <a style="text-decoration: none; font-family: 'Roboto', sans-serif;" href="/">Головна</a>
  </h2>
</div>

<form method="POST" action="" enctype="multipart/form-data">
  <label for="author">Автор</label>
  <input type="text" name="author" id="author"><br>

  <label for="city">Місто</label>
  <input type="text" name="city" id="city"><br>

  <label for="country">Країна</label>
  <input type="text" name="country" id="country"><br>

  <label for="title">Назва історії</label>
  <input type="text" name="title" id="title"><br>

  <label for="text">Текст</label>
  <textarea name="text" id="text"></textarea><br>

  <label for="category">Категорія</label><br>
  <select name="category" id="category">
    <option value="1">Історії із життя біженців</option>
    <option value="2">Історії із життя</option>
  </select>
  
  <label for="image">Зображення</label><br>
  <input type="file" name="image" id="image">

  <button type="submit">Додайти історію</button>
</form>

</body>
</html>
