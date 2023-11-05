<?php

require "config/config.php";

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>History.ua</title>
    <link rel="stylesheet" type="text/css" href= "/cssfiles/new.css">
    <?php include "includes/fonts.php"?>
	</head>

	<body>
    <div class="box">
    <h1 style=font-size:60px;><?php echo $config ['title']; ?></h1>
      <?php include "includes/header.php";?>
      <h1><li class="link"><a href="/articles.php" style="color:white;">Всі історії</a></li></h1>
      <li class="link"><h1><a href="/" style="color:white;">Головна</a></h1></li>

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

        <hr>

        <div class="titleHistory">
          <h1>Нові історії</h1>
        </div>

        <hr>

        <div class="pn">
          <p><span><a href="popular.php" class="pop_new" style="color:white; text-align:center;">Популярні</a></span></p>
        </div>

        <hr>
        
        <div class="block-history">
            <?php 
        $history_n = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY views ASC");

        ?>

        <?php
        while( $art = mysqli_fetch_assoc($history_n) )
        {
        ?>
            <div class="History-block">
              <h3 id="nameHistory" class="history-title"><a class="history-link" href="/article.php?id=<?php echo $art['id']; ?>"><?php echo $art['title']; ?></a></h3>
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
            <div class="view">
            <small>Переглядів:
              <?php echo $art['views']; ?>
            </small>
          </div>
          </div>

              </div>
             <?php
        }
        ?>
  </div>
  <?php include "includes/footer.php"; ?> 
  

  </body>
</html>