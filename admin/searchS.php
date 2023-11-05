<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результати пошуку</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            margin: 20px 0;
            font-size:28px;
        }

        .news-list {
            list-style: none;
            padding: 0;
            display:flex;
            justify-content:center;
        }

        .news-item {
            background-color:#C0C0C0;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            opacity: 0; /* Начальное значение прозрачности для анимации */
            animation: fadeInUp 0.5s ease forwards; /* Анимация появления */
        }

        .news-item:nth-child(odd) {
            animation-delay: 0.1s; /* Задержка анимации для элементов с нечетными номерами */
        }

        @keyframes fadeInUp {
            to {
                opacity: 1; /* Значение прозрачности после завершения анимации */
                transform: translateY(0); /* Смещение элемента вниз для анимации появления */
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
            text-align:center;
            margin-top: 20px;
            font-size:24px;
        }
        img{
            width:400px;
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
        .form-container form input[type="text"],
        .form-container form input[type="file"] {
            margin-bottom: 10px;
            margin-top: 10px;
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
    </style>
</head>
<body>
    <?php
    $user = "root";
    $password = "";
    $host = "localhost";
    $db = "NashKrai";
    $dbn = "mysql:host=$host;dbname=$db;charset=utf8";
    $pdo = new PDO($dbn, $user, $password);
?>
 <?php 
    $results = array(); // Initialize an array for search results

    if (!empty($_POST['search'])) {
        $search = $_POST['search'];

        $stmt = $pdo->prepare("SELECT * FROM `stock` WHERE `name` LIKE :search");
        $stmt->execute(['search' => "%$search%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Output all news
        $sql = $pdo->prepare("SELECT * FROM `stock`");
        $sql->execute();
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
?>
    <div class="search-form">
    <form action="" method="POST">
        <input type="text" name="search" placeholder="Пошук за ім'ям" />
        <input type="submit" value="Пошук" />
    </form>
</div>
<hr>

<ul class="news-list">
    <?php if (count($results) > 0): ?>
        <?php foreach ($results as $result): ?>
            <li class="news-item">
                <!-- Ваш код для вывода новости, оставляем без изменений -->
                <!-- ... -->

                <!-- Кнопки "Зберегти" и "Видалити" -->
                <div class="form-container">
                    <form action="/admin/stock/stock.php/<?php echo $result['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div><?php echo $result['id'] ?></div>
                        <input type="text" name="name" value="<?php echo $result['name'] ?>">
                        <input type="text" name="text" value="<?php echo $result['text'] ?>">
                        <input type="text" name="date" value="<?php echo $result['date'] ?>">
                        <input type="text" name="price" value="<?php echo $result['price'] ?>">
                        <p>
                            <input type="file" name="image" value="<?php echo $result['image'] ?>">
                        </p>
                        <input type="submit" name="save" value="Зберегти">
                    </form>

                    <form class="delete-form" action="/admin/stock/delete.php" method="POST" onsubmit="return confirm('Ви впевнені, що бажаєте видалити?')">
                        <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                        <input type="submit" name="delete" value="Видалити">
                        <input type="hidden" name="confirm" value="yes">
                    </form>
                    <img src="stock/img/<?php echo $result['image'] ?>" alt="Акція" width="300">
                </div>
                
                <?php if (!empty($result['image'])): ?>
                    <div class="media-container">
                        <div class="img">
                            <img src="news/images/<?php echo $result['image'] ?>">
                        </div>
                    </div>
                <?php elseif (!empty($result['image'])): ?>
                    <img src="news/images/<?php echo $result['image'] ?>" width="300">
                <?php endif; ?> 
                
                <hr>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li class="news-item">
            <h3>Нічого не знайдено.</h3>
        </li>
    <?php endif; ?>
</ul>
</body>
</html>
