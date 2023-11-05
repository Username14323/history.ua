<style>
  body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
  }

  #header {
    margin-bottom: 200px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #333;
    color: #fff;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    z-index: 999;
  }

  .logo {
    display: flex;
    align-items: center;
    margin-right: 20px;
    flex-shrink: 0;
  }

  .logo img {
    height: 60px;
  }

  .header_bottom {
    background-color: #222;
    padding: 10px;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }

  nav ul li {
    margin: 0 10px;
  }

  nav ul li a {
    color: #fff;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    background-color: #333;
  }

  nav ul li a:hover {
    background-color: #555;
  }
</style>

<header id="header">
  <?php 
    $categories_q = mysqli_query($connection, "SELECT * FROM `articles_categories`");
    $categories = array();
    while ($cat = mysqli_fetch_assoc($categories_q)) {
      $categories[] = $cat;
    }
    $category1_q = mysqli_query($connection, "SELECT * FROM `articles_categories` WHERE `id` = 1");
    $category1 = mysqli_fetch_assoc($category1_q);
  
    $category2_q = mysqli_query($connection, "SELECT * FROM `articles_categories` WHERE `id` = 2");
    $category2 = mysqli_fetch_assoc($category2_q);
  ?>
  <div class="header_bottom">
    <div class="logo">
      <a href="/">
        <img src="/static/images/H.png" alt="Логотип">
      </a>
    </div>
    <nav>
      <ul>
        <li class="link"><a href="../siteFiles/History ukrainian.php"><?php echo $category1['title']; ?></a></li>
        <li class="link"><a href="../siteFiles/History with life.php"><?php echo $category2['title']; ?></a></li>
        <?php
        if ($loggedIn) {
          echo "<li class='link'><a href='../siteFiles/questionnaire.php'>Додати свою історію</a></li>";
          echo "<li class='link'><a href='../siteFiles/profile.php?login=$userLogin' style='color:white;'>Профіль</a></li>";
          echo "<li class='link'><a href='logout.php' style='color:white;'>Вийти</a></li>";
        } else {
          echo "<li class='link'><a href='../siteFiles/register.html' style='color:white;'>Зареєструватися</a></li>";
          echo "<li class='link'><a href='../siteFiles/Signup.php' style='color:white;'>Вхід</a></li>";
        }
        ?>
        <li class="link"><a href="/articles.php" style="color:white;">Всі історії</a></li>
      </ul>
    </nav>
  </div>
</header>
