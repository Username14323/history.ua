<?php

require "../config/config.php";
session_start();

$loggedIn = false;
$userLogin = "";

// Проверяем, вошел ли пользователь в систему
if (isset($_SESSION['login'])) {
  $loggedIn = true;
  $userLogin = $_SESSION['login'];
}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<title>Ukrainian.ua</title>
		<link rel="stylesheet" type="text/css" href="../cssfiles/styleHU.css">
    <?php include "../includes/fonts.php"?>
	</head>
	
	<body>
	   <div class="box">
		<header>
			<h1 style="font-size: 60px;" id="title">Історії біженців</h1>
			<?php
      include "../includes/header.php";
      ?>
				</ul>
			</nav>
		</header>

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

		<div class="container">
			<div class="history-block">
			<?php 
        $articles = mysqli_query($connection, "SELECT * FROM `articles` WHERE `categorie_id` = 1");
        ?>

        <?php
        while( $art = mysqli_fetch_assoc($articles) )
        {
        ?>
		<div class="history-title">
      <h3><a class="history-link" href="/article.php?id=<?php echo $art['id']; ?>"><?php echo $art['title']; ?></a></h3>
		</div>

    <img src="/static/images/<?php echo $art['image']; ?>" style="float:left; width:200px; height:150px;">
    <div class="view">
                  <small>Переглядів:
                    <?php echo $art['views']; ?>
                  </small>
                </div>
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
                
             <?php
        }
        ?>
        
			</div>
		</div>
	</div>
  
	</div>

	</div>
  <?php include "includes/footer.php"; ?> 
	</body>
</html>