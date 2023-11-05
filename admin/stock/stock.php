<?php
$user = "root";
$password = "";
$host = "localhost";
$db = "NashKrai";
$dbn = "mysql:host=$host;dbname=$db;charset=utf8";
$pdo = new PDO($dbn, $user, $password);

if (isset($_POST['save'])) {
    // Проверяем, было ли загружено новое изображение
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $uploadedExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($uploadedExtension, $allowedExtensions)) {
            exit("Расширение файла не подходит");
        }
        if ($_FILES['image']['size'] >= (1360 * 1020)) {
            exit("Размер файла превышен");
        }
        $upload = 'img/' . $_FILES['image']['name'];
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload)) {
            exit("Ошибка загрузки файла");
        }
        // Обновляем изображение только если загружено новое изображение
        $uploadedImageName = $_FILES['image']['name'];
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('/', $url);
        $str = $url[4];
        $sql = "UPDATE `news` SET name=:name, image=:image, text=:text WHERE id=:id";
        $query = $pdo->prepare($sql);
        $query->execute(['name' => $_POST['name'], 'image' => $uploadedImageName, 'text' => $_POST['text'], 'id' => $str]);
        echo "Файл загружен и данные обновлены";
    } else {
        // Обновляем данные без изображения, если новое изображение не загружено
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('/', $url);
        $str = $url[4];
        $sql = "UPDATE `stock` SET name=:name, image=:image, text=:text, date=:date, price=:price WHERE id=:id";
        $query = $pdo->prepare($sql);
        $query->execute(['name' => $name, 'image' => $_FILES['image']['name'], 'text' => $text, 'date' => $date, 'price' => $price, 'id' => $str]);
        echo "Данные обновлены";
        echo '<meta HTTP-EQUIV="Refresh" Content="0; URL=/admin/stock.php">';
    }
}

$url = $_SERVER['REQUEST_URI'];
$url = explode('/', $url);
$str = $url[4];

$name = $_POST['name'];
$text = $_POST['text'];
$date = $_POST['date'];
$price = $_POST['price'];
$sql = "UPDATE `stock` SET name=:name, image=:image, text=:text, date=:date, price=:price WHERE id=:id";
$query = $pdo->prepare($sql);
$query->execute(['name' => $name, 'image' => $_FILES['image']['name'], 'text' => $text, 'date' => $date, 'price' => $price, 'id' => $str]);
echo '<meta HTTP-EQUIV="Refresh" Content="0; URL=/admin/stock.php">';
?>
