<?php
$user = "root";
$password = "";
$host = "localhost";
$db = "HistoryTime";
$dbn = "mysql:host=$host;dbname=$db;charset=utf8";
$pdo = new PDO($dbn, $user, $password);
?>

<?php 
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$title = $_POST['title'];
$text = $_POST['text'];

// Проверяем, есть ли файл в $_FILES['image']
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    // Добавьте проверки на тип файла и размер, как в вашем первом блоке кода
    // ...

    // Перемещаем загруженное изображение
    $upload = 'image/' . $_FILES['image']['name'];
    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload)) {
        echo "Файл загружен";
        $image = $_FILES['image']['name'];
    } else {
        echo "Ошибка при загрузке файла";
        exit; // Прерываем выполнение скрипта, чтобы не обновлять запись в базе без загруженного файла.
    }
} else {
    // Если файла нет, то оставляем текущее значение изображения в базе данных
    $sql_image = $pdo->prepare("SELECT image FROM `articles` WHERE id = :id");
    $sql_image->execute(['id' => $id]);
    $result = $sql_image->fetch(PDO::FETCH_OBJ);
    $image = $result->image;
}

// Обновляем информацию в базе данных
$sql = "UPDATE `articles` SET title=:title, image=:image, text=:text WHERE id=:id";
$query = $pdo->prepare($sql);
if ($query->execute(['title' => $title, 'image' => $image, 'text' => $text, 'id' => $id])) {
    echo "Запись успешно обновлена";
} else {
    echo "Ошибка при обновлении записи: " . $query->errorInfo()[2];
}

// Перенаправляем обратно на страницу about.php
// echo '<meta HTTP-EQUIV="Refresh" Content="0; URL=/admin/about.php">';
?>
