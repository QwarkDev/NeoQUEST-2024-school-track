<?php 
  if(isset($_SESSION['auth'])) {
    session_destroy();
  }
  if(session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db_path = 'static/instance/database.db';
    $db = new PDO('sqlite:' . $db_path);

    $stmt = $db->prepare('SELECT username, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $user['password'] == hash('sha512', $password)) {
      $_SESSION['auth'] = $username;
      header('Location: notes.php');
      exit();
      
    } else {
      $err = "Неправильный логин или пароль";
    }
  }
?>

<html>
  <head>
    <link rel="stylesheet" href="static/css/main.css">
    <title>Вход</title>
  </head>
  <body>
    <div class="login">
      <h1>Вход</h1>
      <form method="post">
        <input type="text" name="username" placeholder="Username" required="required" />
        <input type="password" name="password" placeholder="Password" required="required" />
        <button type="submit">Login</button>
        <?php if (isset($err)) echo "<h4>$err</h4>"; ?>

        <h5>У вас нет учетной записи? <a class="text-with-underline" href="register.php">Зарегистрироваться</a></h5>
      </form>
    </div>
  </body>
</html>
