<?php
require_once "../config/config.php";

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $storyId = $_POST['story_id'];
    $query = "DELETE FROM articles WHERE id = $storyId";
    mysqli_query($connection, $query);

    // Перенаправление пользователя на главную страницу или другую страницу
    header('Location: /index.php');
    exit();
}

// Получение деталей истории
$storyId = $_GET['id'];
$query = "SELECT * FROM articles WHERE id = $storyId";
$result = mysqli_query($connection, $query);
$story = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Видалення</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #2a2a2a;
            border-radius: 8px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        input[type="text"],
        textarea {
            width: 95%;
            padding: 10px;
            background-color: #333;
            border: none;
            border-radius: 4px;
            color: #ffffff;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Видалення</h1>

    <form method="post">
        <input type="hidden" name="story_id" value="<?php echo $storyId; ?>">
        <label>Заголовок: <input type="text" name="new_title" value="<?php echo $story['title']; ?>"></label>
        <br>
        <label>Текст: <textarea name="new_text"><?php echo $story['text']; ?></textarea></label>
        <br>
        <button type="submit">Так, видалити</button>
    </form>
</div>

</body>
</html>