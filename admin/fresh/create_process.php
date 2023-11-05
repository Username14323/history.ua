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

    $list = ['.php', '.zip', '.rar', '.js', '.html', '.css'];
    foreach ($list as $item) {
        if (preg_match("/$item/", $_FILES['image']['name'])) {
            exit("Расширение файла не подходит");
        }
    }

    $type = getimagesize($_FILES['image']['tmp_name']);
    if ($type && ($type['mime'] == 'image/png' || $type['mime'] == 'image/jpg' || $type['mime'] == 'image/jpeg')) {
        $upload = 'image/' . $_FILES['image']['name'];

        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload)) {
            $sql = "INSERT INTO `fresh` (title, text, image) VALUES (:name, :text, :image)";
            $query = $pdo->prepare($sql);
            $query->execute(['name' => $name, 'text' => $text, 'image' => $_FILES['image']['name']]);
            echo "Новина успішно додана";
        } else {
            echo "Помилка при завантаженні файлу";
        }
    } else {
        exit("Тип файлу не підходить");
    }
}
?>
