<?php
require_once "../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $storyId = mysqli_real_escape_string($connection, $_POST['id']);

    // Проверка на существование записи
    $checkQuery = "SELECT * FROM articles WHERE id=$storyId";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        echo "Помилка: Запись с указанным ID не существует.";
        exit();
    }

    $newTitle = mysqli_real_escape_string($connection, $_POST['title']);
    $newText = mysqli_real_escape_string($connection, $_POST['text']);
    $newCategory = mysqli_real_escape_string($connection, $_POST['category']);
    $newLocation = mysqli_real_escape_string($connection, $_POST['location']);
    $newCountry = mysqli_real_escape_string($connection, $_POST['country']);
    $newImage = null;

    if (!empty($_FILES['image']['name'])) {
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            // Завантажте зображення до сервера
            $targetPath = "../static/images/" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
            $newImage = $_FILES['image']['name'];
        } else {
            echo "Помилка: Неприпустимий тип файлу. Допустимі формати: jpg, jpeg, png, gif";
            exit();
        }
    }

    // Получите старое значение из базы данных
    $query = "SELECT * FROM articles WHERE id=$storyId";
    $result = mysqli_query($connection, $query);
    $story = mysqli_fetch_assoc($result);
    $oldImage = $story['image'];

    // Если не загружена новая картинка, используйте старую
    if ($newImage === null) {
        $newImage = $oldImage;
    }

    // Используйте подготовленный запрос
    $updateQuery = "UPDATE articles SET title=?, text=?, image=?, categorie_id=?, city=?, country=? WHERE id=?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssi", $newTitle, $newText, $newImage, $newCategory, $newLocation, $newCountry, $storyId);
        if (mysqli_stmt_execute($stmt)) {
            // Успешно обновлено
            header('Location: /article.php?id=' . $storyId);
            exit();
        } else {
            echo "Помилка при виконанні запиту: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Помилка при подготовці запиту: " . mysqli_error($connection);
    }
}

// Получение деталей истории
$storyId = $_GET['id'];
$query = "SELECT * FROM articles WHERE id=$storyId";
$result = mysqli_query($connection, $query);
$story = mysqli_fetch_assoc($result);

// Отримання даних категорій з бази даних
$categories_query = mysqli_query($connection, "SELECT * FROM `articles_categories`");
$categories = array();
while ($category = mysqli_fetch_assoc($categories_query)) {
    $categories[] = $category;
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
    body.dark-theme {
    background-color: #1a1a1a;
    color: #ffffff;
}

body.dark-theme a {
    color: #007bff;
}

body.dark-theme .box {
    background-color: #252525;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    padding: 20px;
    color: #ffffff;
}

.edit-form {
    max-width: 400px;
    margin: 0 auto;
    background-color: #252525;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    color: #ffffff;
}

.dark-theme .form-group {
    margin-bottom: 20px;
}

.dark-theme .form-label {
    font-weight: bold;
    display: block;
    margin-bottom: 10px;
    font-size: 18px;
}

.dark-theme .form-input {
    width: 90%;
    padding: 10px;
    border: 1px solid #333333;
    background-color: #333333;
    color: #ffffff;
    border-radius: 4px;
    font-size: 16px;
}

.dark-theme .btn {
    padding: 12px 20px;
    background-color: #007bff;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.dark-theme .btn:hover {
    background-color: #0056b3;
}
</style>
</head>
<body class="dark-theme">
<form method="post" class="edit-form dark-theme" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $storyId; ?>">
    <div class="form-group">
        <label for="title" class="form-label">Заголовок:</label>
        <input type="text" name="title" id="title" class="form-input" value="<?php echo $story['title']; ?>">
    </div>
    <div class="form-group">
        <label for="text" class="form-label">Текст:</label>
        <textarea name="text" id="text" class="form-input"><?php echo $story['text']; ?></textarea>
    </div>
    <div class="form-group">
        <label for="image" class="form-label">Зображення:</label>
        <input type="file" name="image" id="image" class="form-input">
    </div>
    <div class="form-group">
    <label for="category" class="form-label">Категорія:</label>
    <select name="category" id="category" class="form-input">
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $story['categorie_id']) echo "selected"; ?>><?php echo $cat['title']; ?></option>
        <?php endforeach; ?>
    </select>
</div>
    <div class="form-group">
        <label for="location" class="form-label">Місце:</label>
        <input type="text" name="location" id="location" class="form-input" value="<?php echo $story['city']; ?>">
    </div>
    <div class="form-group">
        <label for="country" class="form-label">Країна:</label>
        <input type="text" name="country" id="country" class="form-input" value="<?php echo $story['country']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Зберегти</button>
</form>
</body>
</html>
