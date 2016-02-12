<?php

/*--------------CONTROLLER-------------------------------------------------------------------*/

    function separator() {
         return '<img class="center" src="img/div.png" alt="divisor" width=100% />';
    }
    if(isset($_SESSION['admin']) /*
AND isset($web['description']) 
AND $web['description'] == 'controller'*/) {

/*_____________________SAVE PHP UPDATE_______________________*/
        if(isset($_SESSION['status']) 
AND $_SESSION['status'] == 'root' 
AND isset($_POST['submitPHP']) 
AND isset($_POST['update'])) {
            $query = $pdo->prepare('UPDATE site SET content = :content WHERE keyword = 0 AND name = "php"');
            $query->execute(array('content' => stripslashes($_POST['update'])));
            $query->closeCursor();
            $php = fopen('admin/php.php', 'r+');
            ftruncate($php, 0);
            fputs($php, stripslashes($_POST['update']));
            fclose($php);
        }

/*_____________________PHP EDIT FORM_________________________*/
        $query = $pdo->query('SELECT content FROM site WHERE keyword = 0 AND name = "php"');
        $queryFetch = $query->fetch();
        $php_content = $queryFetch['content'];
        $query->closeCursor();
        $web['phpEditForm'] = '
            <form method=post action="web.php?code='.$page.'&page='.$subpage.'">
                 '.textarea($php_content, "update", 20, 100).'
                 <input name="submitPHP" type=submit value="Sauvegardez" />
            </form>
        ';
    }

/*--------------SCRIPT-----------------------------------------------------------------------*/

    if(isset($_SESSION['admin']) /*AND isset($web['description']) 
AND $web['description'] == 'script'*/) {
        if(!isset($_GET['tmp'])) {
            $_GET['tmp'] = 0;
        }
/*_____________________SAVE SCRIPT UPDATE____________________*/
        if(isset($_SESSION['status']) AND $_SESSION['status'] == 'root' AND isset($_POST['submitSCRIPT']) AND isset($_POST['update'])) {
            $query = $pdo->prepare('UPDATE site SET content = :content WHERE keyword = :keyword AND name = "script"');
            $query->execute(array(
                'content' => stripslashes($_POST['update']),
                'keyword' => $_GET['tmp']
            ));
            $query->closeCursor();
        }

/*_____________________SCRIPT EDIT FORM______________________*/
        $query = $pdo->prepare('SELECT content FROM site WHERE keyword = :keyword AND name = "script"');
        $query->execute(array(
            'keyword' => $_GET['tmp']
        ));
        $queryFetch = $query->fetch();
        $script_content = $queryFetch['content'];
        $query->closeCursor();
        $web['scriptEditForm'] = '
            <form method=post action="web.php?code='.$page.'&page='.$subpage.'&tmp='.$_GET['tmp'].'">
                 '.textarea($script_content, "update", 20, 100).'
                 <input name="submitSCRIPT" type=submit value="Sauvegarder" />
            </form>
        ';
    }

/*--------------STYLE------------------------------------------------------------------------*/

    if(isset($_SESSION['admin']) /*AND isset($web['description']) 
AND $web['description'] == 'style'*/) {

/*__________________________VIEW THEME_________________________*/
        if((isset($_POST['go_to_theme']) OR isset($_POST['newtheme'])) AND isset($_POST['themes'])) {
            $css_name = $_POST['themes'];
        }
        else {
            $css_name = 'standard';
        }

/*_________________________DEFAULT THEME_______________________*/
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

/*______________________NEW THEME______________________________*/
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

/*_______________________SAVE THEME____________________*/
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

/*____________________COPY THEME____________________*/
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

/*_____________________________DELETE THEME_____________________________*/
        if(isset($_POST['delete']) AND isset($_POST['name'])) {
            $query = $pdo->prepare('DELETE FROM themes WHERE name = :name');
            $query->execute(array('name' => $_POST['name']));
            $query->closeCursor();
        }

/*_____________________________CSS EDIT FORM_____________________________*/
        $query = $pdo->prepare('SELECT * FROM themes WHERE name = :name ORDER BY id');
        $query->execute(array('name' => $css_name));
        $queryFetch = $query->fetch();
        $css_content = $queryFetch['content'];
        $css_owner = $queryFetch['comment'];
        $query->closeCursor();
        $web['cssEditForm'] = '
            <form method=post action="web.php?code='.$page.'&page='.$subpage.'">
                <select name="themes">
        ';
        $query = $pdo->query('SELECT * FROM themes ORDER BY name');
        while($queryFetch = $query->fetch()) {
            $web['cssEditForm'] = $web['cssEditForm'].'
                <option value="'.$queryFetch['name'].'"
            ';
            if($css_name == $queryFetch['name']) {
                $web['cssEditForm'] = $web['cssEditForm'].' selected ';
            }
            $web['cssEditForm'] = $web['cssEditForm'].'>';
            if($queryFetch['comment'] == $_SESSION['masterkey']) {
            $web['cssEditForm'] = $web['cssEditForm'].'*';
            }
            $web['cssEditForm'] = $web['cssEditForm'].$queryFetch['name'].'</option>
            ';
        }
        $query->closeCursor();
        $web['cssEditForm'] = $web['cssEditForm'].'</select>
               <input name="go_to_theme" type=submit value="Voir ce thème" />
            </form>
               </br></br>
            <form method=post action="web.php?code='.$page.'&page='.$subpage.'">
               <fieldset>
                    <label>Nouveau thème : </label>
                   <input name="themes" type=text placeholder="Nom du thème" />
                   <input name="newtheme" type=submit value="Créer" />
        ';
        if(isset($_GET['error']) AND $_GET['error'] == 'name') {
            $web['cssEditForm'] = $web['cssEditForm'].'
                </br><p>Ce nom de thème existe déjà.</p>
            ';
        }
        $web['cssEditForm'] = $web['cssEditForm'].'</fieldset>
            </form></br></br>
        '.separator().'<form method=post action="web.php?code='.$page.'&page='.$subpage.'">
                <input name="newname" type=text value="'.$css_name.'" />
                </br>'
                .textarea($css_content, "content", 20, 100)
        ;
        if($css_owner == $_SESSION['masterkey']) {
            $web['cssEditForm'] = $web['cssEditForm'].'
                    <input name="name" type="hidden" value="'.$css_name.'" />
                    <input name="save_css" type=submit value="Sauvegarder" />
            ';
            if($css_name != 'standard') {
             $web['cssEditForm'] = $web['cssEditForm'].'
                <input name="delete" type=submit value="Supprimer"/>
             ';
            }
        }
        $web['cssEditForm'] = $web['cssEditForm'].'
                <input name="copy" type=submit value="Copier ce thème"/>
            </form>
        ';
    }

/*--------------NOTE-------------------------------------------------------------------------*/

/*--------------COUNT------------------------------------------------------------------------*/

/*--------------DOWGS------------------------------------------------------------------------*/
    $locals = 'web.php?code=7&page=1';
    $signupCenterForm =
        '
<form method=post action='.$locals.'>
    <label>Nom du centre :</label>
    <input type=text name="name" value=""/>

    <label>Directeur :</label>
    <input type=text name="director" value=""/>

    <label>Prestige :</label>
    <select name="prestige">
        <option value="no">peu importe</option>
    </select>

    <label>Tarif maximal :</label>


    <input type=submit name="centerSearch" value="Rechercher" />
</form>
        ';
    $shopForm = 
        '
<form method=post action="'..$locals'">

</form>
        ';
?>