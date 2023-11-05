<?php
$login = $_GET['login'];

$connection = mysqli_connect('127.0.0.1', 'root', '', 'HistoryTime');

if (!$connection) {
  echo 'Не вдалося підключитися до бази даних!<br>';
  echo mysqli_connect_error();
  exit();
}

// Получаем информацию о пользователе из базы данных
$user_query = mysqli_query($connection, "SELECT * FROM `users` WHERE `login` = '$login'");
$user = mysqli_fetch_assoc($user_query);

// Проверяем, найден ли пользователь
if (!$user) {
  echo '<div class="message">Пользователь не найден!</div>';
  exit();
}

// Получаем истории, добавленные пользователем
$user_id = $user['id'];
$stories_query = mysqli_query($connection, "SELECT * FROM `articles` WHERE `user_id` = '$user_id'");


session_start();

$loggedIn = false;
$userLogin = "";

// Проверяем, вошел ли пользователь в систему
if (isset($_SESSION['login'])) {
  $loggedIn = true;
  $userLogin = $_SESSION['login'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <?php include "includes/fonts.php"?>
    <style>
        body {
          font-family: "Montserrat", sans-serif !important;
            margin: 0;
            padding: 0;
            background-color: #111;
            color: #ddd;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #333;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }

        h1, h2, h3 {
            color: #fff;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #333;
        }
        .story {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #555;
            background-color: #444;
            border-radius: 10px;
            transition: background-color 0.3s linear;
        }

        .story:hover {
          background-color:#696969;
          cursor:pointer;
        }

        .logout-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff5555;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout-link:hover {
            background-color: #ff3333;
        }

        .container a{
          text-decoration:none;
          color:white;
        }

        .edit-delete-links {
          display: flex;
          justify-content: space-between;
          margin-top: 10px;
      }

      .edit-link, .delete-link {
          padding: 5px 10px;
          border-radius: 5px;
          text-decoration: none;
          font-size: 14px;
          color: #fff;
          transition: background-color 0.3s, color 0.3s;
      }

      .edit-link {
          background-color: #4caf50;
      }

      .delete-link {
          background-color: #f44336;
          margin-left: 10px;
      }

      .edit-link:hover, .delete-link:hover {
          background-color: #333;
          color: #ddd;
      }
      .category-text a {
        color: yellow;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        }

        .category-text a:hover {
        color: navy;
        background-color: #e8f0fe;
        border-radius: 3px;
        padding: 2px;
        }
        .category-text {
        font-size: 20px;
        color: #555;
        font-weight:bold;
        }

        .image img{
            object-fit: cover;
            border-radius: 10px;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
  <?php include "../includes/header.php";?>
    <header>
        <h1>Профіль користувача</h1>
    </header>
    <div class="container">
        <p>Ласкаво просимо на сторінку профілю, <b><?php echo $user['login']; ?></b>!</p>
        <p>Email: <?php echo $user['email']; ?></p>

        <h2>Додані історії:</h2>
        <?php
      while ($story = mysqli_fetch_assoc($stories_query)) {
        echo '<div class="story">';
        echo '<h3><a href="../article.php?id=' . $story['id'] . '">' . $story['title'] . '</a></h3>';
        echo '<p>' . $story['text'] . '</p>';

        echo '<div class="image" style="margin-left:20px">';
        echo '<img src="../static/images/' . $story['image'] . '" alt="Story Image" width="400" height="300">';
        echo '</div>';
        echo '<div class="category-text">';
        $art_cat = false;
          foreach ($categories as $cat) {
            if ($cat['id'] == $story['categorie_id']) {
              $art_cat = $cat;
              break;
            }
          }
          ?>
          <?php if ($story['categorie_id'] == 1): ?>
            <a href="History ukrainian.php"><?php echo $art_cat['title']; ?></a>
          <?php else: ?>
            <a href="History with life.php"><?php echo $art_cat['title']; ?></a>
          <?php endif; ?>
          <?php 
        echo '</div>';
        if ($loggedIn && $userLogin === $login) {
            echo '<div class="edit-delete-links">';
            echo '<a class="edit-link" href="edit.php?id=' . $story['id'] . '">Редагувати</a>';
            echo '<a class="delete-link" href="delete.php?id=' . $story['id'] . '">Видалити</a>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    ?>
    

        <a class="logout-link" href="logout.php">Вийти</a>
    </div>
</body>
</html>



