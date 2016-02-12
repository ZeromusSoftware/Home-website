<?php
    if(isset($_SESSION['status']) AND $_SESSION['status'] == 'root' AND isset($_POST['submitPHP']) AND isset($_POST['update'])) {
        $query = $pdo->prepare('UPDATE site SET content = :content WHERE keyword = 0 AND name = "php"');
        $query->execute(array('content' => $_POST['update']));
        $query->closeCursor();
        $php = fopen('admin/php.php', 'r+');
        ftruncate($php, 0);
        fputs($php, $_POST['update']);
        fclose($php);
    }
?>

<?php
  session_start();
  $page = 'css_editor';
  $page_title ='Editeur de thème';
  require('require/model.php');
  require('require/controller.php');
  require('require/view.php');
?>