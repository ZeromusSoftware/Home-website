<?php
    if(isset($_SESSION['admin']) AND isset($_GET['tmp'])) {
        if(isset($_SESSION['status']) AND $_SESSION['status'] == 'root' AND isset($_POST['submitSCRIPT']) AND isset($_POST['update'])) {
            $query = $pdo->prepare('UPDATE site SET content = :content WHERE keyword = :keyword AND name = "script"');
            $query->execute(array(
                'content' => stripslashes($_POST['update']),
                'keyword' => $_GET['tmp']
            ));
            $query->closeCursor();
        }
        $query = $pdo->prepare('SELECT content FROM site WHERE keyword = :keyword AND name = "script"');
        $query->execute(array(
            'keyword' => $_GET['tmp']
        ));
        $queryFetch = $query->fetch();
        $script_content = $queryFetch['content'];
        $query->closeCursor();
        echo '
            <form method=post action="script_editor.php?tmp='.$_GET['tmp'].'">
                 <textarea name="update" rows=20 cols=100>'.$script_content.'</textarea>
                 </br>
                 <input name="submitSCRIPT" type=submit value="Sauvegarder" />
            </form>
        ';
    }
    else {
        header('Location: site.php');
    }
?>