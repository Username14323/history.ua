<!DOCTYPE html>
<html>
  <head>
    <title>Реєстрація</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <style>
      body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #222;
        color: #fff;
        font-family: Arial, sans-serif;
      }

      .container {
        max-width: 1000px;
        padding: 40px;
        background-color: #333;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        animation: glow 2s ease-in-out infinite alternate;
      }

      @keyframes glow {
        0% {
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        100% {
          box-shadow: 0 0 10px rgba(0, 128, 0, 0.7),
            0 0 20px rgba(0, 128, 0, 0.5), 0 0 30px rgba(0, 128, 0, 0.3);
        }
      }

      h2 {
        text-align: center;
        margin-bottom: 20px;
      }

      form {
        display: flex;
        flex-direction: column;
        width: 500px;
      }

      input[type="text"],
      input[type="password"],
      input[type="email"] {
        padding: 10px;
        margin-bottom: 10px;
        background-color: #444;
        border: none;
        border-radius: 3px;
        color: #fff;
      }

      button {
        padding: 10px 20px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
      }

      button:hover {
        background-color: #45a049;
      }

      .message {
        margin: 20px 0;
        padding: 10px;
        background-color: #ffdddd;
        border: 1px solid #ff0000;
        color: #ff0000;
        text-align: center;
      }
    </style>
  </head>
  <body>
  <body>
    <div class="container">
      <h2>Вхід в адміністративну панель</h2>
      <form action="./admin/admin.php" method="POST">
        <div class="form-group">
            <input type="text" name="login" placeholder="Логін" required />
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Пароль" required />
        </div>
        <div class="form-group">
            <input type="email" name="email" placeholder="Електронна пошта" required />
        </div>
        <button type="submit">Увійти</button>
    </form>
    </div>
  </body>
</html>
