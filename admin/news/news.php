<?php
$user = "root";
$password = "";
$host = "localhost";
$db = "NashKrai";
$dbn = "mysql:host=$host;dbname=$db;charset=utf8";
$pdo = new PDO($dbn, $user, $password);

if (isset($_POST['save'])) {
    $id = isset($_POST['id']) ? $_POST['id'] : 0; // Значение по умолчанию, если id не определен
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $text = isset($_POST['text']) ? $_POST['text'] : '';

    // Проверяем, было ли загружено новое изображение
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $uploadedExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($uploadedExtension, $allowedExtensions)) {
            exit("Расширение файла изображения не подходит");
        }
        $upload = 'images/' . $_FILES['image']['name'];
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload)) {
            exit("Ошибка загрузки изображения");
        }
        $uploadedImageName = $_FILES['image']['name'];
    } else {
        // Получаем текущее изображение, если новое не загружено
        $sql_image = $pdo->prepare("SELECT image FROM `news` WHERE id = :id");
        $sql_image->execute(['id' => $id]);
        $result = $sql_image->fetch(PDO::FETCH_OBJ);
        $uploadedImageName = $result ? $result->image : '';
    }

    // Проверяем, было ли загружено новое видео
    if ($_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $allowedVideoExtensions = ['mp4', 'avi', 'mov'];
        $uploadedVideoExtension = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        if (!in_array($uploadedVideoExtension, $allowedVideoExtensions)) {
            exit("Расширение файла видео не подходит");
        }
        $uploadVideo = 'videos/' . $_FILES['video']['name'];
        if (!move_uploaded_file($_FILES['video']['tmp_name'], $uploadVideo)) {
            exit("Ошибка загрузки видео");
        }
        $uploadedVideoName = $_FILES['video']['name'];
    } else {
        // Получаем текущее видео, если новое не загружено
        $sql_video = $pdo->prepare("SELECT video FROM `news` WHERE id = :id");
        $sql_video->execute(['id' => $id]);
        $result = $sql_video->fetch(PDO::FETCH_OBJ);
        $uploadedVideoName = $result ? $result->video : '';
    }

    // Обновляем информацию в базе данных
    $sql = "UPDATE `news` SET name=:name, image=:image, video=:video, text=:text WHERE id=:id";
    $query = $pdo->prepare($sql);
    $query->execute(['name' => $name, 'image' => $uploadedImageName, 'video' => $uploadedVideoName, 'text' => $text, 'id' => $id]);
    echo "Данные обновлены";
}
    // echo '<meta HTTP-EQUIV="Refresh" Content="0; URL=/admin/news.php">';
?>

<!-- Ваш код HTML с формой -->
