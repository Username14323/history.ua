<?php
session_start();
$user = "root";
$password = "";
$host = "localhost";
$db = "NashKrai";
$dbn = "mysql:host=$host;dbname=$db;charset=utf8";
$pdo = new PDO($dbn, $user, $password);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM `news` WHERE id=:id";
    $query = $pdo->prepare($sql);
    $query->execute(['id' => $id]);
    echo "Новина успішно видалена";
} else {
    echo "Помилка: не вказано ID новини";
}
?>
