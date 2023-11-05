<?php require_once "../config/connect.php"; ?>
<?php session_start(); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST["login"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Хешируем пароль (рекомендуется использовать более безопасные методы хеширования)
    $hashedPassword = md5($password);

    $sql = $pdo->prepare("SELECT id, login FROM users WHERE login=:login AND password=:password");
    $sql->execute(['login' => $login, 'password' => $hashedPassword]);

    $array = $sql->fetch(PDO::FETCH_ASSOC);

    if ($array) { // В данном случае достаточно проверить, что массив не пустой
        $_SESSION['login'] = $array['login'];
        // Сохраняем email в сессии для дальнейшего использования, если необходимо
        $_SESSION['email'] = $email;
        header('Location: /admin.php');
        exit;
    } else {
        // Пользователь не найден, добавляем нового пользователя в базу данных
        $insertSql = $pdo->prepare("INSERT INTO users (login, email, password) VALUES (:login, :email, :password)");
        $insertSql->execute(['login' => $login, 'email' => $email, 'password' => $hashedPassword]);

        // Перенаправляем пользователя на страницу входа после успешной регистрации
        header('Location: /login.php');
        exit;
    }
}
?>
