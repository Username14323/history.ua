<?php
require "../config/config.php";
session_start();

if (isset($_SESSION['login'])) {
    $loggedIn = true;
    $userLogin = $_SESSION['login'];

    if (isset($_POST['do_edit_comment'])) {
        $commentId = (int)$_POST['comment_id'];
        $editedComment = mysqli_real_escape_string($connection, $_POST['edited-comment']);

        // Проверьте, принадлежит ли комментарий текущему пользователю, чтобы избежать возможных атак
        $commentQuery = mysqli_query($connection, "SELECT author FROM `comments` WHERE `id` = $commentId LIMIT 1");
        if ($commentQuery) {
            $commentData = mysqli_fetch_assoc($commentQuery);
            if ($commentData['author'] === $userLogin) {
                // Обновите комментарий в базе данных
                mysqli_query($connection, "UPDATE `comments` SET `text` = '$editedComment' WHERE `id` = $commentId");
            }
        }

        // Перенаправьте пользователя обратно на страницу статьи
        header("Location: /article.php?id=" . (int)$_GET['article_id']);
        exit();
    }
} else {
    // Пользователь не вошел в систему, выполните необходимые действия для аутентификации
    header("Location: ../siteFiles/Signup.php");
    exit();
}
?>
