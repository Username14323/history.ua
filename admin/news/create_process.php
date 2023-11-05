<?php
session_start();
?>

<?php
$user = "root";
$password = "";
$host = "localhost";
$db = "NashKrai";
$dbn = "mysql:host=$host;dbname=$db;charset=utf8";
$pdo = new PDO($dbn, $user, $password);

if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $text = $_POST['text'];
    $uploadImage = 'images/' . $_FILES['image']['name'];
    $uploadVideo = 'videos/' . $_FILES['video']['name'];

    // Проверяем расширение загружаемого изображения
    $allowedImageExtensions = ['png', 'jpg', 'jpeg'];
    $uploadedImageExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($uploadedImageExtension, $allowedImageExtensions)) {
        exit("Расширение файла изображения не подходит");
    }

    // Проверяем тип загружаемого изображения
    $imageType = getimagesize($_FILES['image']['tmp_name']);
    if (!$imageType || !in_array($imageType['mime'], ['image/png', 'image/jpg', 'image/jpeg'])) {
        exit("Тип файла изображения не подходит");
    }

    // Проверяем расширение загружаемого видео
    $allowedVideoExtensions = ['mp4', 'avi', 'mov'];
    $uploadedVideoExtension = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
    if (!in_array($uploadedVideoExtension, $allowedVideoExtensions)) {
        exit("Расширение файла видео не подходит");
    }

    // Загружаем изображение и видео
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadImage) &&
        move_uploaded_file($_FILES['video']['tmp_name'], $uploadVideo)) {

        $sql = "INSERT INTO `news` (name, text, image, video) VALUES (:name, :text, :image, :video)";
        $query = $pdo->prepare($sql);
        $query->execute(['name' => $name, 'text' => $text, 'image' => $_FILES['image']['name'], 'video' => $_FILES['video']['name']]);
        echo "Новина успішно додана";
    } else {
        echo "Помилка при завантаженні файлів";
    }
}
?>
