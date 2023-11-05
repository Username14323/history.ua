<?php
require "config/config.php";
session_start();

$loggedIn = false;
$userLogin = "";
$viewedHistories = array();

// Проверяем, вошел ли пользователь в систему
if (isset($_SESSION['login'])) {
    $loggedIn = true;
    $userLogin = $_SESSION['login'];
}

// Проверка, авторизован ли пользователь
$loggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Функция для обновления времени просмотра статьи
function updateViewedTime($connection, $userId, $articleId) {
    $query = "INSERT INTO `viewed_histories` (`user_id`, `article_id`, `viewed_at`) 
              VALUES ($userId, $articleId, NOW()) 
              ON DUPLICATE KEY UPDATE `viewed_at` = NOW()";
    mysqli_query($connection, $query);
}

// При просмотре статьи
if ($loggedIn && isset($_GET['article_id'])) {
    $userId = $_SESSION['user_id'];
    $articleId = $_GET['article_id'];
    // Обновляем время просмотра для данной статьи
    updateViewedTime($connection, $userId, $articleId);
}

// Обнуляем просмотренные истории, у которых время просмотра истекло (более чем 5 часов назад)
if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    mysqli_query($connection, "UPDATE `viewed_histories` SET `viewed_at` = NOW() WHERE `user_id` = $userId AND `viewed_at` < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
}

// Получаем просмотренные истории для пользователя
if ($loggedIn) {
    $userId = $_SESSION['user_id'];
    $viewedHistoriesQuery = mysqli_query($connection, "SELECT * FROM `viewed_histories` WHERE `user_id` = $userId");

    $viewedHistories = array();

    while ($row = mysqli_fetch_assoc($viewedHistoriesQuery)) {
        $viewedHistories[] = $row['article_id'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>History.ua</title>
    <link rel="stylesheet" type="text/css" href="cssfiles/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image.png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image.png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <?php include "includes/fonts.php"?>
</head>

<body>
<div class="sidebar">
    <h2>Переглянуті історії</h2>
    <ul>
        <?php
        if (empty($viewedHistories)) {
            echo "<p>Немає переглянутих історій</p>";
        } else {
            foreach ($viewedHistories as $historyId) {
                $historyQuery = mysqli_query($connection, "SELECT * FROM `articles` WHERE `id` = $historyId");
                $history = mysqli_fetch_assoc($historyQuery);
                if ($history) {
                  echo "<li>";
                  echo "<img src='/static/images/{$history['image']}' alt='{$history['title']}' width='50' height='50'>";
                  echo "<a href='/article.php?id={$history['id']}'>{$history['title']}</a>";
                  echo "</li>";
                }
            }
        }
        ?>
    </ul>
</div>

    <div class="box">
        <?php
        if ($loggedIn) {
            echo "<div class='user-info'>
              <p class='user-welcome'>Ви увійшли в систему як: $userLogin</p>
              <a class='logout-link' href='./siteFiles/logout.php'>Вийти</a>
            </div>";
        } else {
            echo "<div class='user-info'>
              <a class='login-link' href='./siteFiles/Signup.php'>Увійти</a>
            </div>";
        }
        ?>
    
        <h1 class="titleText" style=font-size:60px;><?php echo $config['title']; ?></h1>
        <?php include "includes/header.php"; ?>
        <div class="content">
            <div class="main-content">
                <div class="generalTitle">
                    <h2 style="font-size:50px;">Головна</h2>
                </div>
    
                <hr>
    
                <div class="ser">
                    <form method="post" class="search-form">
                        <input type="text" name="search" placeholder="Пошук...">
                        <button type="submit">Знайти</button>
                    </form>
    
                    <?php
                    if (isset($_POST['search'])) {
                        $search = $_POST['search'];
                        $query = mysqli_query($connection, "SELECT * FROM `articles` WHERE `title` LIKE '%$search%' OR `text` LIKE '%$search%' ");
                        while ($row = mysqli_fetch_assoc($query)) {
                            $title = $row['title'];
                            $text = $row['text'];
                            $short_text = mb_substr($text, 0, 300, 'utf-8') . "...";
                            echo "<h3 class='history-title'><a class='history-link' href='/article.php?id=" . $row['id'] . "' style='float:center;'>" . $title . "</a></h3> <p>" . $short_text . "</p><br>";
                        }
                    }
                    ?>
                </div>
    
                <div class="titleHistory">
                    <h1 style="font-size:40px;">Історії</h1>
                </div>
    
                <hr>
    
                <div class="pn">
                    <p><a href="/popular.php" class="pop_new" style="color:white; text-align:center;">Популярні</a><span><a href="/new.php" class="pop_new" style="color:white; text-align:center;">Нові</a></span></p>
                </div>
    
                <hr>
    
                <div class="block-history">
                    <?php
                    $history = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id` DESC LIMIT 10");
                    while ($art = mysqli_fetch_assoc($history)) {
                        ?>
                        <div class="history-block">
                            <h3 class="history-title"><a class="history-link" href="/article.php?id=<?php echo $art['id']; ?>"style="float:center;"><?php echo $art['title']; ?></a></h3>
                            <img src="/static/images/<?php echo $art['image']; ?>"
                                 style="float:left; width:200px; height:150px;">
                            <div class="history_image" style="background-image: url(/static/images/<?php echo $art['image']; ?>);"></div>
                            <div class="info_history_preview">
                                <p><?php echo mb_substr($art['text'], 0, 100, 'utf-8') . " ..."; ?></p>
                            </div>
                            <div class="category-history">
                                <?php
                                $art_cat = false;
                                foreach ($categories as $cat) {
                                    if ($cat['id'] == $art['categorie_id']) {
                                        $art_cat = $cat;
                                        break;
                                    }
                                }
                                ?>
                                <div class="category-text">
                                    <small>Категорія:
                                        <?php if ($art['categorie_id'] == 1): ?>
                                            <a href="siteFiles/History ukrainian.php"><?php echo $art_cat['title']; ?></a>
                                        <?php else: ?>
                                            <a href="siteFiles/History with life.php"><?php echo $art_cat['title']; ?></a>
                                        <?php endif; ?>
                                    </small>
                                    <div class="view">
                                        <small>Переглядів:
                                            <?php echo $art['views']; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        </div>
</body>
</html>
