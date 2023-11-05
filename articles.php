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
    <link rel="stylesheet" type="text/css" href="cssfiles/articles.css">
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
          <a class='logout-link' href='logout.php'>Вийти</a>
        </div>";
      }  else {
        echo "<div class='user-info'>
          <a class='login-link' href='login.php'>Увійти</a>
        </div>";
      }
?>
    <h1 style=font-size:60px;><?php echo $config ['title']; ?></h1>
      <?php include "includes/header.php";?>
      <hr>
        <div class="generalTitle">
         <h2 style="font-size:50px;">Всі історії</h2>
        </div>
        <hr>
        <div class="ser">
          <form method="post" class="search-form">
            <input type="text" name="search" placeholder="Пошук...">
            <button type="submit">Знайти</button>
          </form>

          <?php
          if(isset($_POST['search'])){
            $search = $_POST['search'];
            $query = mysqli_query($connection, "SELECT * FROM `articles` WHERE `title` LIKE '%$search%' OR `text` LIKE '%$search%' ");
            while($row = mysqli_fetch_assoc($query)) {
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
        $per_page = 8;
        $page = 1;

        if ( isset($_GET['page']) ){
            $page = (int) $_GET['page'];
        }

        $total_count_q = mysqli_query($connection, "SELECT COUNT(`id`) AS `total_count` FROM `articles`");
        $total_count = mysqli_fetch_assoc($total_count_q);
        $total_count = $total_count['total_count'];

        $total_pages = ceil($total_count / $per_page);
        if ( $page <= 1 || $page > $total_pages ){
            $page = 1;
        }

        $offset = ($per_page * $page) - $per_page;

        $history = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id` DESC LIMIT $offset, $per_page");
    ?>

<?php
        $articles_exist = true;
        if( mysqli_num_rows($history) <= 0 ){
            echo 'Історій немає :(';
            $articles_exist = false;
        }
        while( $art = mysqli_fetch_assoc($history) )
        {
    ?>
            <div class="history-block">
              <h3 class="history-title"><a class="history-link" href="/article.php?id=<?php echo $art['id']; ?>" style="float:center;"><?php echo $art['title']; ?></a></h3>
              <img src="/static/images/<?php echo $art['image']; ?>" style="float:left; width:200px; height:150px;">
              <div class="history_image" style="background-image: url(/static/images/<?php echo $art['image']; ?>);"></div>
              <div class="info_history_preview">
                <p><?php echo mb_substr($art['text'], 0, 100, 'utf-8') . " ..."; ?></p>
              </div>
              <div class="category-history">
              <?php
                $art_cat = false;
                foreach( $categories as $cat)
                {
                  if( $cat['id'] == $art['categorie_id'])
                  {
                    $art_cat = $cat;
                    break;
                  }
                }
                ?>
                <div class="category-text">
                  <small>Категорія: 
                  <?php if ($art['categorie_id'] == 1): ?>
                <a href="/History ukrainian.php"><?php echo $art_cat['title']; ?></a>
              <?php else: ?>
                <a href="/History with life.php"><?php echo $art_cat['title']; ?></a>
              <?php endif; ?>
            </small>
          </div>
          <div class="view">
            <small>Переглядів:
              <?php echo $art['views']; ?>
            </small>
          </div>
              </div>
             <?php
        }
        ?>
            </div>
  <?php
    if ($articles_exist == true) {
        echo '<div class="paginator">';
        if ($page > 1) {
            echo '<a href="/articles.php?page=' . ($page - 1) . '" class="paginator-link paginator-link-prev">&laquo; Попередня сторінка</a>';
        }
        if ($page < $total_pages) {
            echo '<a href="/articles.php?page=' . ($page + 1) . '" class="paginator-link paginator-link-next">Слідуюча сторінка &raquo;</a>';
        }
        echo '</div>';
}
?>
        </div>
        <div class="djer" style="background-color: #F8F8F8; padding: 20px; border: 1px solid #EAEAEA;">
  <h1 style="font-size: 26px; margin: 0; padding-bottom: 10px;">
    <a href="https://tvoemisto.tv/exclusive/hochu_dyhaty_svoim_povitryam_i_dopomagaty_vdoma_istoriya_bizhenky_yaka_povernulasya_dodomu_132022.html" style="color: #333; text-decoration: none; transition: color 0.3s ease-in-out;">
      Деякі історії були взяті з цього джерела...
    </a>
  </h1>
</div>
    </div>
  </body>
</html>