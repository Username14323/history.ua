<?php session_start(); ?>
<?php require_once "../config/connect.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування акцій</title>
    <style>
        body {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 16px;
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    margin: 0;
}

.container {
    max-width: 800px;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 36px;
    color: #333;
    margin-bottom: 30px;
}

img {
    margin-top: 30px;
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.form-container {
    margin-bottom: 40px;
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
    padding: 12px;
    width: 100%;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f5f5f5;
}

.form-container form input[type="submit"] {
    background-color: #00008B;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border-radius: 4px;
    width: 100%;
}

.form-container form input[type="submit"]:hover {
    background-color: #191970;
}

.navigation {
    margin-bottom: 20px;
}

.navigation a {
    margin-right: 10px;
    color: #444;
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
    padding: 12px 20px;
    font-size: 16px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border-radius: 4px;
}

.logout-button a:hover {
    background-color: #d32f2f;
}

.warning-message {
    font-size: 16px;
    margin-top: 20px;
    color: #e74c3c;
}

.form-container img {
    margin-top: 30px;
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: block;
    margin-left: auto;
    margin-right: auto;
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
    <div class="container">
        <h1>Редагування акцій</h1>
        <div class="navigation">
                <a href="/">Головна</a>
                <a href="/admin/news.php">Новини</a>
                <a href="/admin/about.php">Про нас</a>
                <a href="/admin/stock.php">Акції</a>
                <a href="/admin/stock/createNew.php">Додати</a>
                <a href="/admin/fresh.php">Слайдер</a>
            </div>
        <?php if (!empty($_SESSION['login'])): ?>
            <div class="logout-button">
                <a href="/logout.php">Вийти</a>
            </div>
            <div class="search-form">
            <form action="/admin/searchS.php" method="POST">
                <input type="text" name="search" placeholder="Пошук за ім'ям новини" />
                <input type="submit" value="Пошук" />
            </form>
        </div>
            <br>

            <?php 
                $sql = $pdo->prepare("SELECT * FROM `stock`");
                $sql->execute();
                $hasStocks = $sql->rowCount() > 0;
            ?>

            <?php if ($hasStocks): ?>
                <?php while ($res = $sql->fetch(PDO::FETCH_OBJ)): ?>
                    <div class="form-container">
                        <form action="/admin/stock/stock.php/<?php echo $res->id ?>" method="POST" enctype="multipart/form-data">
                            <div><?php echo $res->id ?></div>
                            <input type="text" name="name" value="<?php echo $res->name ?>">
                            <input type="text" name="text" value="<?php echo $res->text ?>">
                            <input type="text" name="date" value="<?php echo $res->date ?>">
                            <input type="text" name="price" value="<?php echo $res->price ?>">
                            <p>
                                <input type="file" name="image" value="<?php echo $res->image ?>">
                            </p>
                            <input type="submit" name="save" value="Зберегти">
                        </form>

                        <form class="delete-form" action="/admin/stock/delete.php" method="POST" onsubmit="return confirm('Ви впевнені, що бажаєте видалити?')">
                            <input type="hidden" name="id" value="<?php echo $res->id ?>">
                            <input type="submit" name="delete" value="Видалити">
                            <input type="hidden" name="confirm" value="yes">
                        </form>
                        <img src="stock/img/<?php echo $res->image ?>" alt="Акція" width="300">
                    </div>
                    
                    <hr>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Немає акцій для відображення</p>
            <?php endif; ?>

        <?php else: ?>
            <h1>Ти шо, хакер?</h1>
            <a href="/">На головну</a>
        <?php endif; ?>
    </div>
</body>
</html>
