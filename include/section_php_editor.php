<?php
    if(isset($_SESSION['admin'])) {
        if(isset($_SESSION['status']) AND $_SESSION['status'] == 'root' AND isset($_POST['submitPHP']) AND isset($_POST['update'])) {
            $query = $pdo->prepare('UPDATE site SET content = :content WHERE keyword = 0 AND name = "php"');
            $query->execute(array('content' => stripslashes($_POST['update'])));
            $query->closeCursor();
            $php = fopen('admin/php.php', 'r+');
            ftruncate($php, 0);
            fputs($php, stripslashes($_POST['update']));
            fclose($php);
        }
        $query = $pdo->query('SELECT content FROM site WHERE keyword = 0 AND name = "php"');
        $queryFetch = $query->fetch();
        $php_content = $queryFetch['content'];
        $query->closeCursor();
        echo '
            <form method=post action="php_editor.php">
                 <textarea name="update" rows=20 cols=100>'.$php_content.'</textarea>
                 </br>
                 <input name="submitPHP" type=submit value="Sauvegarder" />
            </form>
        ';
    }
    else {
        header('Location: site.php');
    }
?>