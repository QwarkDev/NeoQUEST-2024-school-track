<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $username = $_SESSION['auth'];
  }
  if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $note = $_POST['note'];
    $dir = "notes/notes_{$username}";

    $files = scandir($dir);
    $nextNoteNumber = 0;

    foreach ($files as $file) {
      if ($file != '.' && $file != '..') {
        $noteNumber = intval(substr($file, 4, -4));
        if ($noteNumber >= $nextNoteNumber) {
          $nextNoteNumber = $noteNumber + 1;
        }
      }
    }
  
    if (strlen($note) > 1048576) {
      $note = "Размер заметки превышает 1 МБ";
    }
  
    $filePath = "{$dir}/note{$nextNoteNumber}.txt";
    file_put_contents($filePath, $note);
    
    header('Location: notes.php');
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Создать заметку</title>
  <link rel="stylesheet" href="static/css/main.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
  <div class="tabsNav">
    <a class="navItem" href="notes.php">Мои заметки</a>
    <a class="navItem" href="create_note.php">Создать заметку</a>
  </div>
  <h1 class="indexTitle">Создать заметку</h1>

  <div class="createContent">
    <form class="createForm" action="create_note.php" method="post">
      <textarea class="createText" id="note-textarea" name="note"></textarea>
      <input class="createInput" id="note-submit" type="submit" value="Create">
    </form>
  </div>
</body>
</html>