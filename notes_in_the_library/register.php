<?php
  $db_path = 'static/instance/database.db';
  $db = new PDO('sqlite:' . $db_path);

  // Обработка отправленной формы
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = hash('sha512', $_POST['password']);

    // Вставка нового пользователя в базу данных
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
      mkdir("notes/notes_$username");
      header('Location: login.php');
    } else {
      $err = "Ошибка при регистрации пользователя.";
    }
  }
?>
<html>
  <head>
    <link rel="stylesheet" href="static/css/main.css">
    <title>Регистрация</title>
  </head>
  <body>
    <div class="login">
      <h1>Регистрация</h1>
      <form method="post">
        <input type="text" name="username" placeholder="Username" required="required" />
        <input type="password" name="password" placeholder="Password" required="required" />
        <input type="password" name="repeat_password" placeholder="Repeat password" required="required" />
        <button type="submit">Создать аккаунт</button>
        <?php if (isset($err)) echo "<h4>$err</h4>"; ?>

        <h5>У вас уже есть учетная запись?  <div class="text-with-underline" href="login.php" >Войти</div></h5>
      </form>
    </div>
  </body>
</html>
