<?php session_start();?>
<?php require_once "./config/connect.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ</title>
        <style>
         body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        
        .admin-container {
            max-width: 500px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .admin-title {
            font-size: 36px;
            margin-bottom: 30px;
        }

        .admin-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .admin-links a {
            text-decoration: none;
            color: #333333;
            padding: 10px 20px;
            border-radius: 4px;
            background-color: #e2e2e2;
            transition: background-color 0.3s ease;
        }

        .admin-links a:hover {
            background-color: #d1d1d1;
        }

        .admin-logout {
            display: block;
            text-align: center;
            margin-top: 30px;
        }

        .admin-logout a {
            text-decoration: none;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 4px;
            background-color: #ff4444;
            transition: background-color 0.3s ease;
        }

        .admin-logout a:hover {
            background-color: #ff3333;
        }
    </style>
</head>
<body>

<?php 
if(!empty($_SESSION['login'])):?>
        <br>
        <div class="admin-container">
            <h1 class="admin-title">Ви адмін <?php echo $_SESSION['login']; ?></h1>
            
            <div class="admin-links">
                <a href="/">Головна</a>
                <a href="/admin/HistoryWithLife.php">Історії з життя</a>
                <a href="#">Історії біженців</a>
                <a href="#">Акаунти</a>
                <a href="#">Коментарі</a>
            </div>
            
            <div class="admin-logout">
                <a href="/logout.php">Вийти</a>
            </div>
        </div>
        
    <div class="admin-error">
    <?php else:
        echo '<h1 style="display:block">Ти шо, хакер?</h1>';
        echo '<a href="/" class="admin-err" style="text-decoration:none; background-color:gray; color:black; font-size:20px">На головну</a>';
    ?>
    </div>
<?php endif ?>
</body>
</html>
