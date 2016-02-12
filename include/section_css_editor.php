<?php
    if(isset($_SESSION['admin'])) {
        if((isset($_POST['go_to_theme']) OR isset($_POST['newtheme'])) AND isset($_POST['themes'])) {
            $css_name = $_POST['themes'];
        }
        else {
            $css_name = 'standard';
        }
        if(isset($_GET['code'])) {
            $query = $pdo->prepare('SELECT content FROM site WHERE name = "style_name" AND keyword = :keyword');
            $query->execute(array('keyword' => $_GET['code']));
            $queryFetchStyle = $query->fetch();
            $css_name = $queryFetchStyle['content'];
            $query->closeCursor();
            if(!isset($queryFetchStyle['content'])) {
                $css_name = 'standard';
            }
        }
        if(isset($_POST['newtheme']) AND isset($_POST['themes'])) {
            $query = $pdo->prepare('SELECT name FROM themes WHERE name = :name');
            $query->execute(array('name' => $_POST['themes']));
            $queryTest = $query->fetch();
            if($queryTest['name'] != $css_name) {
                $query = $pdo->prepare('INSERT INTO themes(name, content) VALUES(:name, :content)');
                $query->execute(array(
                    'name' => $_POST['themes'],
                    'content' => '/*Ecrivez ici en CSS votre propre thème. :)*/'
                ));
                $query->closeCursor();
            }
            else {
                header('Location: '.$page.'.php?error=name');
            }
        }
        if(isset($_POST['save_css']) AND isset($_POST['newname']) AND isset($_POST['content']) AND isset($_POST['name'])) {
            if($_POST['newname'] != $_POST['name']) {
                $query = $pdo->prepare('SELECT name FROM themes WHERE name = :name');
                $query->execute(array('name' => $_POST['newname']));
                $queryFetchName = $query->fetch();
                if(isset($queryFetchName['name'])) {
                    $test = false;
                    $i = 0;
                    while($test == false) {
                        $query->execute(array('name' => $_POST['newname'].' '.$i));
                        $queryFetchName = $query->fetch();
                        if(!isset($queryFetchName['name'])) {
                            $newName = $_POST['newname'].' '.$i;
                            $test = true;
                        }
                        else {
                            $i++;
                        }
                    }
                }
                else {
                    $newName = $_POST['newname'];
                }
            }
            else {
                $newName = $_POST['name'];
            }
            $query = $pdo->prepare('UPDATE themes SET name = :newname, content = :content WHERE name = :name');
            $query->execute(array(
                'newname' => $newName,
                'content' => $_POST['content'],
                'name' => $_POST['name']
            ));
            $query->closeCursor();
            $css_name = $newName;
        }
        if(isset($_POST['copy']) AND isset($_POST['newname']) AND isset($_POST['content']) AND isset($_POST['name'])) {
                $query = $pdo->prepare('SELECT name FROM themes WHERE name = :name');
                $query->execute(array('name' => $_POST['name'].' - copy'));
                $queryFetchName = $query->fetch();
                if(isset($queryFetchName['name'])) {
                    $test = false;
                    $i = 0;
                    while($test == false) {
                        $query->execute(array('name' => $_POST['name'].' - copy '.$i));
                        $queryFetchName = $query->fetch();
                        if(!isset($queryFetchName['name'])) {
                            $newName = $_POST['name'].' - copy '.$i;
                            $test = true;
                        }
                        else {
                            $i++;
                        }
                    }
                }
                else {
                    $newName = $_POST['name'].' - copy';
                }
            $query = $pdo->prepare('INSERT INTO themes(name, content, comment) VALUES(:name, :content, :comment)');
            $query->execute(array(
                'name' => $newName,
                'content' => $_POST['content'],
                'comment' => $_SESSION['masterkey']
            ));
            $query->closeCursor();
            $css_name = $newName;
        }
        if(isset($_POST['delete']) AND isset($_POST['name'])) {
            $query = $pdo->prepare('DELETE FROM themes WHERE name = :name');
            $query->execute(array('name' => $_POST['name']));
            $query->closeCursor();
        }
        $query = $pdo->prepare('SELECT * FROM themes WHERE name = :name ORDER BY id');
        $query->execute(array('name' => $css_name));
        $queryFetch = $query->fetch();
        $css_content = $queryFetch['content'];
        $css_owner = $queryFetch['comment'];
        $query->closeCursor();
        echo '
            <form method=post action="css_editor.php">
                <select name="themes">
        ';
        $query = $pdo->query('SELECT * FROM themes ORDER BY name');
        while($queryFetch = $query->fetch()) {
            echo '
                <option value="'.$queryFetch['name'].'"
            ';
            if($css_name == $queryFetch['name']) {
                echo ' selected ';
            }
            echo '>';
            if($queryFetch['comment'] == $_SESSION['masterkey']) {
            echo '*';
            }
            echo $queryFetch['name'].'</option>
            ';
        }
        $query->closeCursor();
        echo '</select>
               <input name="go_to_theme" type=submit value="Voir ce thème" />
            </form>
               </br></br>
            <form method=post action="css_editor.php">
               <fieldset>
                    <label>Nouveau thème : </label>
                   <input name="themes" type=text placeholder="Nom du thème" />
                   <input name="newtheme" type=submit value="Créer" />
        ';
        if(isset($_GET['error']) AND $_GET['error'] == 'name') {
            echo '
                </br><p>Ce nom de thème existe déjà.</p>
            ';
        }
        echo '</fieldset>
            </form></br></br>
        ';
        separator();
        echo '<form method=post action="css_editor.php">
                <input name="newname" type=text value="'.$css_name.'" />
                </br>
                <textarea name="content" rows=20 cols=100>'.$css_content.'</textarea>
                </br>
        ';
        if($css_owner == $_SESSION['masterkey']) {
            echo '
                    <input name="name" type="hidden" value="'.$css_name.'" />
                    <input name="save_css" type=submit value="Sauvegarder" />
            ';
            if($css_name != 'standard') {
             echo '
                <input name="delete" type=submit value="Supprimer"/>
             ';
            }
        }
        echo '
                <input name="copy" type=submit value="Copier ce thème"/>
            </form>
        ';
    }
?>