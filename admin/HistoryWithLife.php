<?php session_start(); ?>
<?php require_once "../config/connect.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование про нас</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-size: 30px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-bottom: 20px;
        }

        a {
            color: #000;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #4CAF50;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        input[type="text"],
        input[type="file"] {
            margin-bottom: 10px;
            padding: 8px;
            width: 300px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            background-color: #f5f5f5;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        img {
            margin-top: 20px;
            max-width: 300px;
        }
    </style>
</head>
<body>
<h1>Редактирование про нас</h1>
<div class="navigation">
        <a href="/">Головна</a>
        <a href="/admin/HistoryWithLife/HistoryWithLife.php">Історії з життя</a>
        <a href="#">Історії біженців</a>
        <a href="#">Акаунти</a>
        <a href="#">Коментарі</a>
    </div>
<?php if (!empty($_SESSION['login'])): ?>
    <?php echo "Ви адмін " . $_SESSION['login']; ?>
    <br>
    <a href="/logout.php">Вийти</a>

    <br>

    <?php 
    $sql = $pdo->prepare("SELECT * FROM `articles`");
    $sql->execute();
    $res = $sql->fetch(PDO::FETCH_OBJ);
?>

<form action="/admin/HistoryWithLife/HistoryWithLife.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $res->id ?>">
    <input type="text" name="title" value="<?php echo $res->title ?>">
    <textarea name="text"><?php echo $res->text ?></textarea>
    <p>
        <input type="file" name="image" value="<?php echo $res->image ?>">
    </p>
    <input type="submit" name="save" value="Зберегти">
</form>

    <img src="/admin/HistoryWIthLife/image/<?php echo $res->image ?>" width="300">


<?php else:
    echo '<h1>Ти шо, хакер?</h1>';
    echo '<a href="/">На головну</a>';
    ?>

<?php endif ?>
</body>
</html>
