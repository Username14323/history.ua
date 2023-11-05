<?php session_start(); ?>
<?php
$user = "root";
$password = "";
$host = "localhost";
$db = "NashKrai";
$dbn = "mysql:host=$host;dbname=$db;charset=utf8";
$pdo = new PDO($dbn, $user, $password);
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .logout-link {
            text-align: right;
            margin-bottom: 10px;
            font-size: 14px;
            text-decoration: none;
            color: #666;
        }

        .navigation {
            text-align: center;
            margin-bottom: 20px;
        }

        .navigation a {
            display: inline-block;
            margin: 0 10px;
            font-size: 14px;
            text-decoration: none;
            color: #666;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="file"],
        input[type="submit"] {
            margin-top: 10px;
            padding: 10px;
            font-size: 14px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .warning-message {
            font-size: 16px;
            margin-top: 20px;
            color: #e74c3c;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати новину</title>
</head>
<body>
<div class="container">
        <h1>Додати новину</h1>
        <?php if (!empty($_SESSION['login'])): ?>
            <div><?php echo "Ви адмін " . $_SESSION['login']; ?></div>
            <br>
            <a class="logout-link" href="/logout.php">Вийти</a>

            <form action="/admin/fresh/create_process.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Назва" required>
                <textarea name="text" placeholder="Текст" required></textarea>
                <input type="file" name="image" required>
                <input type="submit" name="save" value="Додати">
            </form>

            <?php else:
                echo '<h1>Ти шо, хакер?</h1>';
                echo '<a href="/">На головну</a>';
            ?>
        <?php endif ?>
    </div>
</body>
</html>
