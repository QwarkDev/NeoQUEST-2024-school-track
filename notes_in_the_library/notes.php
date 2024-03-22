<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $username = $_SESSION['auth'];
  }
  if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
  }

  $dir = "notes/notes_$username";
  $files = scandir($dir);

  if (isset($_GET['download']) && !empty($_GET['download'])) {
    $filename = $_GET['download'];
    $file = "notes/notes_$username/$filename";

    if (file_exists($file)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.basename($file).'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      readfile($file);
      exit;
    }
  }
?>

<!DOCTYPE html>
  <html>
    <head>
      <link rel="stylesheet" href="static/css/main.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      <title>Мои заметки</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>
  <body>
    <div class="tabs">
      <div class="tabsNav">
        <a class="navItem" href="notes.php">Мои заметки</a>
        <a class="navItem" href="create_note.php">Создать заметку</a>
      </div>
      <div class="tabs-stage">
        <div id="tab-1">
        <h1 class="indexTitle">Notes for <?php echo $username; ?></h1>

        <table class="indexList">
          <tr>
            <th>Filename</th>
            <th>Size</th>
            <th>Modified</th>
          </tr>
          <?php
            foreach ($files as $file) {
              if (is_file("{$dir}/{$file}")) {
                $filePath = "{$dir}/{$file}";
                $fileSize = filesize($filePath);
                $fileModified = filemtime($filePath);
          ?>
              <tr>
                <td><a href="notes.php?download=<?php echo $file; ?>"><?php echo $file; ?></a></td>
                <td><?php echo $fileSize; ?> bytes</td>
                <td><?php echo date('Y-m-d H:i:s', $fileModified); ?></td>
              </tr>
          <?php
              }
            }
          ?>
        </table>
        </div>
      </div>
    </div>

    <div class="logout">
      <a href="index.php" style="cursor: pointer; color: #000">
        Выйти
      </a>
    </div>
  </body>
</html>