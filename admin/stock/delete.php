<?php
session_start();?>
<?php
$user = "root";
    $password = "";
    $host = "localhost";
    $db = "NashKrai";
    $dbn = "mysql:host=$host;dbname=$db;charset=utf8";
    $pdo = new PDO($dbn, $user, $password);
?>
<?php
session_start();
if (isset($_POST['delete']) && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $id = $_POST['id'];
    echo "Акція успішно видалена";
    $sql = "DELETE FROM `stock` WHERE id=:id";
    $query = $pdo->prepare($sql);
    $query->execute(['id' => $id]);
} else {
    echo "Помилка: Ви не підтвердили видалення.";
}
