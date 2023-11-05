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

if (!empty($_SESSION['login'])) {
    if (isset($_POST['save'])) {
        $name = $_POST['name'];
        $text = $_POST['text'];
        $date = $_POST['date'];
        $price = $_POST['price'];

        if ($_FILES['image']['name'] != '') {
            $image = $_FILES['image']['name'];
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            if (!in_array($imageFileType, $allowedExtensions)) {
                exit("Помилка: Дозволені тільки файли JPG, JPEG та PNG.");
            }

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                exit("Помилка: Не вдалося завантажити файл.");
            }
        } else {
            $image = '';
        }

        $sql = "INSERT INTO `stock` (name, text, date, price, image) VALUES (:name, :text, :date, :price, :image)";
        $query = $pdo->prepare($sql);
        $query->execute([
            'name' => $name,
            'text' => $text,
            'date' => $date,
            'price' => $price,
            'image' => $image
        ]);

        echo "Новий акційний товар успішно додано.";
    }
} else {
    echo "Ти шо, хакер?";
}
?>
