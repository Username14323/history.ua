<?php session_start(); ?>
<?php require_once "../config/connect.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування новин</title>
    <style>
    body {
  font-size: 16px;
  line-height: 1.6;
  font-family: Arial, sans-serif;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
  background-color: #f0f0f0;
}

h1 {
  margin-bottom: 20px;
  color: #333;
}

img {
  margin-bottom: 20px;
  max-width: 100%;
}

.form-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
}

.form-container form {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
}

.form-container form input[type="text"],
.form-container form input[type="file"] {
  margin-bottom: 10px;
  padding: 8px;
  width: 300px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.form-container form input[type="submit"] {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  border-radius: 4px;
}

.form-container form input[type="submit"]:hover {
  background-color: #45a049;
  transform: scale(1.05);
}

.navigation {
  margin-bottom: 20px;
}

.navigation a {
  margin-right: 10px;
  color: #4CAF50;
  text-decoration: none;
  font-size: 18px;
  transition: color 0.3s ease;
}

.navigation a:hover {
  color: #333;
}

.logout-button {
  margin-top: 20px;
}

.logout-button a {
  background-color: #f44336;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s ease, box-shadow 0.2s ease;
  border-radius: 4px;
}

.logout-button a:hover {
  background-color: #d32f2f;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}


    </style>
</head>
<body>
<h1>Редагування слайдер</h1>
<div class="navigation">
        <a href="/">Головна</a>
        <a href="/admin/news.php">Новини</a>
        <a href="/admin/about.php">Про нас</a>
        <a href="/admin/fresh/createFresh.php">Додати</a>
        <a href="/admin/stock.php">Акції</a>
        <a href="/admin/fresh.php">Слайдер</a>
    </div>
<?php if (!empty($_SESSION['login'])): ?>
    <?php echo "Ви адмін " . $_SESSION['login']; ?>
    <br>
    <div class="logout-button">
        <a href="/logout.php">Вийти</a>
    </div>

    <br>

    <?php 
        $sql = $pdo->prepare("SELECT * FROM `fresh`");
        $sql->execute();
        while($res = $sql->fetch(PDO::FETCH_OBJ)):
    ?>

    <div class="form-container">
        <form action="/admin/fresh/fresh.php/<?php echo $res->id ?>" method="POST" enctype="multipart/form-data">
            <?php echo $res->id ?>
            <input type="text" name="name" value="<?php echo $res->title ?>">
            <input type="text" name="text" value="<?php echo $res->text ?>">
            <input type="file" name="image" value="<?php echo $res->image ?>">
            <input type="submit" name="save" value="Зберегти">
        </form>

        <form action="/admin/fresh/delete.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $res->id ?>">
            <input type="submit" name="delete" value="Видалити">
        </form>
    </div>

    <img src="fresh/image/<?php echo $res->image ?>" width="300">

    <hr>
    <?php endwhile; ?>

<?php else:
    echo '<h1>Ти шо, хакер?</h1>';
    echo '<a href="/">На головну</a>';
    ?>

<?php endif ?>
</body>
</html>
