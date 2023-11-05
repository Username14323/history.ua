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
            height: 100vh;
            font-size: 30px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        
        .box {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            overflow: hidden;
            margin-top: 40px;
            }

        h1 {
            margin-bottom: 20px;
        }

        img {
            margin-bottom: 80px;
            margin-top: 50px;
            max-width: 500px;
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
            border: none;
            border-radius: 4px;
            background-color: #f5f5f5;
        }

        .form-container form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 4px;
        }

        .form-container form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .navigation {
            margin-bottom: 20px;
        }

        .navigation a {
            margin-right: 10px;
            color: #000;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .navigation a:hover {
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
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
            transition: background-color 0.3s ease;
            border-radius: 4px;
        }

        .logout-button a:hover {
            background-color: #d32f2f;
        }

        .media-container {
        display: block;
        justify-content: center;
        margin-bottom: 20px;
        }

        .media-container img {
            margin-right: 10px;
        }

        .media-container video {
            max-width: 560px;
        }

        .search-form {
            text-align: center;
            margin: 20px 0;
        }

        .search-form input[type="text"] {
            padding: 8px 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
            transition: box-shadow 0.3s;
        }

        .search-form input[type="text"]:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(108, 92, 231, 0.5);
        }

        .search-form input[type="submit"] {
            background-color: #6c5ce7;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px;
            margin-left: 10px;
        }

        .search-form input[type="submit"]:hover {
            background-color: #5247c0;
        }

        .news-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .news-item {
            border: 1px solid #ddd;
            background-color:#D3D3D3;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 400px;
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }

        .news-item:nth-child(odd) {
            animation-delay: 0.1s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .news-item h3 {
            margin: 0;
            font-size: 22px;
            text-align: center;
        }

        .news-item img,
        .news-item video {
            max-width: 100%;
            border-radius: 5px;
            display: block;
            margin: 10px auto;
        }

        .news-item p {
            margin: 10px 0;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="box">
<h1>Редагування новин</h1>
<div class="navigation">
    <a href="/">Головна</a>
    <a href="/admin/news.php">Новини</a>
    <a href="/admin/about.php">Про нас</a>
    <a href="/admin/news/createNew.php">Додати новину</a>
    <a href="/admin/stock.php">Акції</a>
    <a href="/admin/fresh.php">Слайдер</a>
</div>
<?php if (!empty($_SESSION['login'])): ?>
    <?php echo "Ви адмін " . $_SESSION['login']; ?>
    <br>
    <div class="logout-button">
        <a href="/logout.php">Вийти</a>
    </div>
    <div class="search-form">
            <form action="/admin/search.php" method="POST">
                <input type="text" name="search" placeholder="Пошук за ім'ям новини" />
                <input type="submit" value="Пошук" />
            </form>
        </div>
        <hr>
    <br>
    <hr>

    <?php 
        $sql = $pdo->prepare("SELECT * FROM `news`");
        $sql->execute();
        while($res = $sql->fetch(PDO::FETCH_OBJ)):
    ?>
    <div class="form-container">
        <form action="/admin/news/news.php/<?php echo $res->id ?>" method="POST" enctype="multipart/form-data">
            <?php echo $res->id ?>
            <hr>
            <?php echo $res->name ?>
            <input type="hidden" name="id" value="<?php echo $res->id ?>">
            <input type="text" name="name" value="<?php echo $res->name ?>">
            <input type="text" name="text" value="<?php echo $res->text ?>">
            <h4>Додати фото</h4>
            <input type="file" name="image" value="<?php echo $res->image ?>">
            <h4>Додати відео</h4>
            <input type="file" name="video" value="<?php echo $res->video ?>">
            <input type="submit" name="save" value="Зберегти">
        </form>

        <form action="/admin/news/delete.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $res->id ?>">
            <input type="submit" name="delete" value="Видалити">
        </form>
    </div>
    
    <?php if (!empty($res->image) && !empty($res->video)): ?>
    <div class="media-container">
        <div class="img">
            <img src="news/images/<?php echo $res->image ?>">
        </div>
        <div class="video">
            <video width="560" height="315" controls>
                <source src="news/videos/<?php echo $res->video ?>" type="video/mp4">
                Ваш браузер не поддерживает воспроизведение видео.
            </video>
        </div>
    </div>
    <?php elseif (!empty($res->image)): ?>
        <img src="news/images/<?php echo $res->image ?>" width="300">
    <?php elseif (!empty($res->video)): ?>
        <video width="560" height="315" controls>
            <source src="news/videos/<?php echo $res->video ?>" type="video/mp4">
            Ваш браузер не поддерживает воспроизведение видео.
        </video>
    <?php endif; ?> 
    
    <hr>
    <?php endwhile; ?>

<?php else:
    echo '<h1>Ти шо, хакер?</h1>';
    echo '<a href="/">На головну</a>';
    ?>

<?php endif ?>
</div>
</body>
</html>