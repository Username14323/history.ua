<?php
require "config/config.php";
session_start();

$loggedIn = false;
$userLogin = "";

if (isset($_SESSION['login'])) {
    $loggedIn = true;
    $userLogin = $_SESSION['login'];
}

if (isset($_GET['id'])) {
    $articleId = (int)$_GET['id'];

    if ($loggedIn) {
        $userId = $_SESSION['user_id'];
        $query = "INSERT INTO `viewed_histories` (`user_id`, `article_id`) VALUES ($userId, $articleId) ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
        mysqli_query($connection, $query);
    }
}

if (isset($_SESSION['login'])) {
    $loggedIn = true;
    $userLogin = $_SESSION['login'];
}

if (isset($_GET['id'])) {
    $articleId = (int)$_GET['id'];
} else {
    // Вывод сообщения об ошибке или перенаправление на страницу с ошибкой.
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_edited_comment'])) {
    if ($loggedIn) {
        $commentIdToSave = (int)$_POST['edit_comment_id'];
        $editedComment = mysqli_real_escape_string($connection, $_POST['edited_comment']);

        mysqli_query($connection, "UPDATE `comments` SET `text` = '$editedComment' WHERE `id` = $commentIdToSave");

        header("Location: /article.php?id=$articleId");
        exit();
    } else {
        // Вывод сообщения об ошибке или перенаправление на страницу аутентификации.
    }
}

if (isset($_POST['delete_comment'])) {
    $commentIdToDelete = (int)$_POST['delete_comment'];

    $commentQuery = mysqli_query($connection, "SELECT `author` FROM `comments` WHERE `id` = $commentIdToDelete");
    if ($commentQuery) {
        $commentData = mysqli_fetch_assoc($commentQuery);

        if ($commentData['author'] === $_SESSION['login']) {

            mysqli_query($connection, "DELETE FROM `comments` WHERE `id` = $commentIdToDelete");

            header("Location: /article.php?id=" . $articleId);
            exit();
        } else {
            // Пользователь не имеет права удалять этот комментарий
            // Ничего не делаем, чтобы избежать вывода ошибки
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>History.ua</title>
    <link rel="stylesheet" type="text/css" href="/cssfiles/article.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <?php include "includes/fonts.php" ?>
</head>

<body>
    <div class="box">
        <h1 style="font-size: 60px;"><?php echo $config['title']; ?></h1>
        <?php include "includes/header.php"; ?>
        <div class="block-history">
            <?php
            $history = mysqli_query($connection, "SELECT * FROM `articles` WHERE `id` = " . (int)$_GET['id']);
            if (mysqli_num_rows($history) <= 0) {
            ?>
                <div class="History-block">
                    <h2>Стаття не знайдена!</h2>
                    <h3 id="nameHistory"></h3>
                    <div class="history_image"></div>
                    <div class="info_history_preview">
                        <p>Стаття, яку Ви шукаєте, не знайдена!</p>
                    </div>
                    <div class="category-history">
                    </div>
                </div>
            <?php } else {
                $art = mysqli_fetch_assoc($history);
                mysqli_query($connection, "UPDATE `articles` SET `views` = `views` + 1 WHERE `id` = " . (int) $art['id']);
            ?>
                <hr>
                <div class="History-block">
                    <h1><?php echo $art['views'] ?> переглядів</h1>
                    <hr>
                    <h2><?php echo $art['title'] ?></h2>
                    <div style="display: flex; justify-content: center;">
                        <img src="/static/images/<?php echo $art['image']; ?>" style="max-width: 100%;">
                    </div>

                    <div class="history_text">
                        <p style="font-size: 20px;"><?php echo $art['text'] ?></p>
                    </div>
                    <div class="info_history_preview">
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
                                <?php if ($art['categorie_id'] == 1) : ?>
                                    <a href="/History ukrainian.php"><?php echo $art_cat['title']; ?></a>
                                <?php else : ?>
                                    <a href="/History with life.php"><?php echo $art_cat['title']; ?></a>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    <hr>
                    <div class="comments">
                        <h2>Коментарі</h2>
                        <div class="comments_content">
                    <div class="comm">
                        <?php
                        $comments = mysqli_query($connection, "SELECT * FROM comments WHERE articles_id = $articleId ORDER BY id DESC");
                        if (mysqli_num_rows($comments) <= 0) {
                            echo "Коментарів немає";
                        }
                while ($comment = mysqli_fetch_assoc($comments)) {
                ?>
                    <article class="history" id="comment-<?php echo $comment['id']; ?>">
                        <img src="/static/images/user.png" style="float: left; width: 50px;">
                        <div class="history_image" style="background-image: url(<?php echo md5($comment['email']); ?>?s=125);">
                        </div>
                        <div class="info">
                            <a style="text-align: center; font-size: 20px;"
                                href="/article.php?id=<?php echo $comment['articles_id']; ?>"><?php echo $comment['author']; ?></a>
                        </div>
                        <div class="comment_text">
                            <p style="font-size: 15px;"
                                id="comment-text-<?php echo $comment['id']; ?>"><?php echo $comment['text']; ?></p>
                        </div>
                        <?php if ($loggedIn && $_SESSION['login'] == $comment['author']) : ?>
                        <form method="post" action="/article.php?id=<?php echo $articleId; ?>">
                            <input type="hidden" name="edit_comment_id" value="<?php echo $comment['id']; ?>">
                            <textarea name="edited_comment"><?php echo $comment['text']; ?></textarea>
                            <button type="submit" name="save_edited_comment">Сохранить</button>
                            <button type="submit" name="delete_comment" value="<?php echo $comment['id']; ?>">Удалить</button>
                        </form>
                    <?php endif; ?>
                    </article>
                <?php } ?>
                </div>
            </div>
                    </div>
                    <hr>
                    <?php if ($loggedIn) : ?>
                        <div id="comment-add-form" class="block-comment">
                            <h3>Додати коментар</h3>
                            <div class="block_content">
                                <form class="ct" action="/article.php?id=<?php echo $art['id']; ?>#comment-add-form" method="post">
                                    <?php
                                    if (isset($_POST['do_post'])) {
                                        $errors = array();

                                        if ($_POST['name'] == '') {
                                            $errors[] = "Напишіть ім'я!";
                                        }

                                        if ($_POST['nickname'] == '') {
                                            $errors[] = 'Напишіть нікнейм!';
                                        }

                                        if ($_POST['email'] == '') {
                                            $errors[] = 'Напишіть email!';
                                        }

                                        if ($_POST['comment'] == '') {
                                            $errors[] = 'Напишіть текст!';
                                        }

                                        if (empty($errors)) {
                                            // Додамо коментар
                                            mysqli_query($connection, "INSERT INTO `comments` (`author`, `nickname`, `email`, `text`, `pubdate`, `articles_id`) VALUES ('" . $_POST['name'] . "', '" . $_POST['nickname'] . "', '" . $_POST['email'] . "', '" . $_POST['comment'] . "', NOW(), '" . $art['id'] . "')");

                                            echo '<span style="color:green;font-weight:bold;margin-bottom:10px;display:block;">Коментар додано!</span>';
                                        } else {
                                            echo '<span style="color:red;font-weight:bold;margin-bottom:10px;display:block;">' . array_shift($errors) . '</span>';
                                        }
                                    }
                                    ?>

                                    <div class="form-group">
                                        <label for="name">Автор</label>
                                        <input type="text" class="form-control" name="name" id="name" value="<?php echo @$_POST['name']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="nickname">Нікнейм</label>
                                        <input type="text" class="form-control" name="nickname" id="nickname" value="<?php echo @$_POST['nickname']; ?>">
                                    </div>

                                    <div class="form-label">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo @$_POST['email']; ?>">
                                    </div>

                                    <div class="form-label">
                                        <label for="comment">Текст коментаря</label>
                                        <textarea name="comment" id="comment" class="form-control"><?php echo @$_POST['comment']; ?></textarea>
                                    </div>
                                    <div class="btn">
                                        <button id="btn" type="submit" name="do_post" class="btn btn-default">Додати коментар</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php else : ?>
                      <div class="register-link">
                        <p>Для того, щоб залишити коментар, будь ласка, <a href="/siteFiles/Signup.php">увійдіть в систему</a>.</p>
                      </div>
                    <?php endif; ?>

                </div>
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>
