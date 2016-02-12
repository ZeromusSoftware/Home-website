<?php
    session_start();
/*----------------------------------------------------MODEL-------------------------------------------------------------*/
/*-------Website Info-----------*/
    require('require/info.php');
/*-------Stop system------------*/
    if($stop) {
        header('Location: stop.html');
    }
/*-------Data base name---------*/
  if(isset($localhost)) {
    if(!$localhost) {
      $dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
      try {
         $pdo = new PDO($dsn, $admin_login, $admin_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
      }
      catch(PDOException $exception) {
        exit('DBtest log in failure. </br>
               Localhost mode: disable');
      }
    }
    else {
      $dsn = 'mysql:host='.$host_local.';dbname='.$dbname_local.';charset=utf8';
      try {
          $pdo = new PDO($dsn, $admin_login_local, $admin_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
      }
      catch(PDOException $exception) {
          exit('DBtest log in failure. </br>
                 Localhost mode: enable');
      }
    }
  }
  else {
    exit('Localhost problem');
  }
/*-------Content system---------*/
    if(isset($_GET['code'])) {
        $page = $_GET['code'];
        $sectionNumber = 0;
        $query = $pdo->prepare('SELECT content FROM site WHERE keyword = :keyword AND page = 0 AND name = "home"');
        $query->execute(array('keyword' => $page));
        $queryHome = $query->fetch();
        $query->closeCursor();
        if(isset($queryHome['content'])) {
            $web['home'] = $queryHome['content'];
        }
        else {
            $web['home'] = 0;
        }
        if(isset($_GET['page'])) {
            $query = $pdo->prepare('SELECT page FROM site WHERE keyword = :keyword AND page = :page');
            $query->execute(array(
                'keyword' => $page,
                'page' => $_GET['page']
            ));
            $queryTestPage = $query->fetch();
            $query->closeCursor();
            if(isset($queryTestPage['page']) AND $queryTestPage['page'] == $_GET['page']) {
                $subpage = $_GET['page'];
            }
            else {
                header('Location: web.php?code='.$page);
            }
        }
        else {
            $subpage = $web['home'];
        }
/*-------New visit---------*/
        if(isset($_SERVER["REMOTE_ADDR"])) {
            $stats = $pdo->prepare('INSERT INTO stats(keyword, date, time, page, ip)
                                    VALUES(:keyword, :date, :time, :page, :ip)');
            $stats->execute(array('keyword' => $page,
                                   'date' => date('d-m-Y'),
                                   'time' => date('H:i:s'),
                                   'page' => 'web.php?code='.$page.'&page='.$subpage,
                                   'ip' => $_SERVER['REMOTE_ADDR']
                                   ));
            $stats->closeCursor();
        }
/*-------View system---------*/
        if(isset($_POST['go_to_page']) AND isset($_POST['direction'])) {
            header('Location: web.php?code='.$page.'&page='.$_POST['direction']);
        }
        $model = $pdo->prepare('SELECT * FROM site WHERE keyword = :keyword AND page = :page');
        $model->execute(array(
            'keyword' => $page,
            'page' => $subpage
        ));
        $i = 0;
        $functionArray = array();
        while($view = $model->fetch()) {
            if($view['comment'] == 'section' OR $view['comment'] == 'function') {
                $sectionNumber++;
                if($view['number'] != $sectionNumber) {
                    $change = $pdo->prepare('UPDATE site SET name = :name, number = :number WHERE id = :id');
                    $change->execute(array(
                        'name' => 'section'.$sectionNumber,
                        'number' => $sectionNumber,
                        'id' => $view['id']
                    ));
                    $change->closeCursor();
                    header('Location: web.php?code='.$page.'&page='.$subpage);
                }
                if($view['comment'] == 'function') {
                    $functionArray[$i] = 'section'.$sectionNumber;
                    $i++;
                }
            }
            $web[$view['name']] = $view['content'];
        }
        $model->closeCursor;
        if(!isset($web['title'])) {
            header('Location: web.php');
        }
        if(isset($_SESSION['editor']) AND $_SESSION['editor']) {
            $web['style_name'] = 'silky white';
        }
        $model = $pdo->prepare('SELECT content FROM themes WHERE name = :name');
        $model->execute(array('name' => $web['style_name']));
        $view = $model->fetch();
        $web['style'] = $view['content'];
        $model->closeCursor();
    }
    else {
        header('Location: site.php');
    }
/*----------------------------------------------------CONTROLLER-------------------------------------------------------------*/
/*-----Admin mode------*/
    if(isset($page) AND isset($_SESSION['masterkey'])) {
        $test = $pdo->prepare('SELECT content FROM site WHERE keyword = :keyword AND page = :page AND owner = :owner AND name = "title"');
        $test->execute(array(
            'keyword' => $page,
            'page' => $subpage,
            'owner' => $_SESSION['masterkey']
        ));
        $testFetch = $test->fetch();
        if($testFetch['content'] == $web['title']) {
            $_SESSION['owner'] = true;
        }
        else {
            $_SESSION['owner'] = false;
            $_SESSION['editor'] = false;
        }
        $test->closeCursor();
    }
        if(isset($web['chmod'])) {
            if($web['chmod'] == 'admin') {
                if(!isset($_SESSION['admin'])) {
                    header('Location: login.php');
                }
            }
            else {
                if($web['chmod'] == 'user') {
                    if(!isset($_SESSION['user'])) {
                        header('Location: login.php');
                    }
                }
                if($web['chmod'] == 'owner') {
                    if(!isset($_SESSION['owner']) OR !$_SESSION['owner']) {
                        header('Location: login.php');
                    }
                }
            }
        }
/*-----Enable editor mode------*/
    if(isset($_GET['editor']) AND $_GET['editor'] == 'true') {
        $_SESSION['editor'] = true;
        header('Location: web.php?code='.$page.'&page='.$subpage);
    }
/*-----Disable editor mode----*/
    if(isset($_GET['editor']) AND $_GET['editor'] == 'false') {
        $_SESSION['editor'] = false;
        header('Location: web.php?code='.$page.'&page='.$subpage);
    }
/*-----Submit system----*/
    if(isset($_SESSION['owner']) AND $_SESSION['owner']) {
        if(isset($_SESSION['status']) AND $_SESSION['status'] == 'root' AND isset($_POST['submitPHP']) AND isset($_POST['update'])) {
            $query = $pdo->prepare('UPDATE site SET content = :content WHERE keyword = 0 AND name = "php"');
            $query->execute(array('content' => stripslashes($_POST['update'])));
            $query->closeCursor();
            $php = fopen('admin/php.php', 'r+');
            ftruncate($php, 0);
            fputs($php, stripslashes($_POST['update']));
            fclose($php);
        }
        if(isset($_POST['submit']) AND isset($_POST['save'])) {
            $update = $pdo->prepare('UPDATE site SET content = :update WHERE name = :submit AND keyword = :keyword AND page = :page');
            $update->execute(array(
                'update' => stripslashes($_POST['update']),
                'submit' => $_POST['submit'],
                'keyword' => $page,
                'page' => $subpage
            ));
            $update->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
        if(isset($_POST['submit']) AND isset($_POST['saveAll'])) {
            $update = $pdo->prepare('UPDATE site SET content = :update WHERE name = :submit AND keyword = :keyword');
            $update->execute(array(
                'update' => stripslashes($_POST['update']),
                'submit' => $_POST['submit'],
                'keyword' => $page
            ));
            $update->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
        if(isset($_POST['submit']) AND isset($_POST['delete'])) {
            $delete = $pdo->prepare('DELETE FROM site WHERE name = :submit AND keyword = :keyword AND page = :page');
            $delete->execute(array(
                'submit' => $_POST['submit'],
                'keyword' => $page,
                'page' => $subpage
            ));
            $delete->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
        if(isset($_POST['deletePage']) AND $_SESSION['owner']) {
            $delete = $pdo->prepare('DELETE FROM site WHERE keyword = :keyword AND page = :page');
            $delete->execute(array(
                'keyword' => $page,
                'page' => $subpage
            ));
            $delete->closeCursor();
            header('Location: web.php?code='.$page);
        }
        if(isset($_POST['newSection']) OR isset($_POST['newFunction'])) {
            if(isset($_POST['newFunction'])) {
                $newComment = 'function';
            }
            else {
                $newComment = 'section';
            }
            $new = $pdo->prepare('INSERT INTO site(keyword, page, owner, name, number, comment) VALUE(:keyword, :page, :owner, :name, :number, :comment)');
            $new->execute(array(
                'keyword' => $page,
                'page' => $subpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'section1',
                'number' => 1,
                'comment' => $newComment
            ));
            $new->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
        if(isset($_POST['newPage'])) {
            $query = $pdo->prepare('SELECT page FROM site WHERE keyword = :keyword ORDER BY page DESC');
            $query->execute(array('keyword' => $page));
            $newSubpageArray = $query->fetch();
            $query->closeCursor();
            $newSubpage = $newSubpageArray['page'];
            $newSubpage++;
            $new = $pdo->prepare('INSERT INTO site(keyword, page, owner, name, content) VALUE(:keyword, :page, :owner, :name, :content)');
            $new->execute(array(
                'keyword' => $page,
                'page' => $newSubpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'title',
                'content' => $web['title']
            ));
            $new->execute(array(
                'keyword' => $page,
                'page' => $newSubpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'description',
                'content' => 'Nouvelle page'
            ));
            $new->execute(array(
                'keyword' => $page,
                'page' => $newSubpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'style_name',
                'content' => $web['style_name']
            ));
            $new->execute(array(
                'keyword' => $page,
                'page' => $newSubpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'chmod',
                'content' => $web['chmod']
            ));
            $new->execute(array(
                'keyword' => $page,
                'page' => $newSubpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'nav',
                'content' => $web['nav']
            ));
            if(isset($web['footer'])) {
                $foo = $web['footer'];
            }
            else {
                $foo = 'disable';
            }
            $new->execute(array(
                'keyword' => $page,
                'page' => $newSubpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'footer',
                'content' => $foo
            ));
            if(isset($web['script'])) {
                $scr = $web['script'];
            }
            else {
                $scr = "";
            }
            $new->execute(array(
                'keyword' => $page,
                'page' => $newSubpage,
                'owner' => $_SESSION['masterkey'],
                'name' => 'script',
                'content' => $scr
            ));
            $new->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$newSubpage);
        }
        if(isset($_POST['change_style']) AND isset($_POST['keyword'])) {
            $query = $pdo->prepare('UPDATE site SET content = :update WHERE name = "style_name" AND keyword = :keyword AND page = :page');
            $query->execute(array(
                'update' => $_POST['update'],
                'keyword' => $page,
                'page' => $subpage,
            ));
            $query->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
        if(isset($_POST['privacy']) AND isset($_POST['chmod'])) {
            $query = $pdo->prepare('UPDATE site SET content = :update WHERE name = "chmod" AND keyword = :keyword AND page = :page');
            $query->execute(array(
                'update' => $_POST['chmod'],
                'keyword' => $page,
                'page' => $subpage
            ));
            $query->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
        if(isset($_POST['newHome']) AND isset($_POST['direction'])) {
            $query = $pdo->prepare('UPDATE site SET content = :update WHERE name = "home" AND keyword = :keyword');
            $query->execute(array(
                'update' => $_POST['direction'],
                'keyword' => $page
            ));
            $query->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
        if(isset($_POST['enableFooter'])) {
            $query = $pdo->prepare('UPDATE site SET content = :update WHERE name = "footer" AND keyword = :keyword AND page = :page');
            if(isset($web['footer']) AND $web['footer'] != 'disable') {
                $query->execute(array(
                    'update' => 'disable',
                    'keyword' => $page,
                    'page' => $subpage
                ));
            }
            else {
                $query->execute(array(
                    'update' => '',
                    'keyword' => $page,
                    'page' => $subpage
                ));
            }
            $query->closeCursor();
            header('Location: web.php?code='.$page.'&page='.$subpage);
        }
    }
/*-----Functions------*/
    function textForm($input, $web, $page, $subpage) {
      $size = strlen($web[$input]);
      echo '<form method=post action="web.php?code='.$page.'&page='.$subpage.'" class="textForm" id="textForm'.$input.'">
                <fieldset>
              <input  name="update" class="admin" size='.$size.' type=text value="'.$web[$input].'" />
              <input name="submit" type="hidden" value="'.$input.'" />
              <input type=submit name="save" value="Sauvegarder" />';
      if($input != 'title' AND $input != 'description') {
          echo '<input type=submit name="delete" value="Supprimer" />
             </fieldset>';
      }
      echo '
              </fieldset>
            </form>';
    }
    function articleForm($input, $web, $page, $subpage) {
      echo '<form method=post action="web.php?code='.$page.'&page='.$subpage.'">
              <textarea name="update" rows=10 cols=100>'.$web[$input].'</textarea></br>
              <input name="submit" type="hidden" value="'.$input.'" />
              <fieldset class="edit">
              <input type=submit name="save" value="Sauvegarder" />';
      if($input != 'description' AND $input != 'nav' AND $input != 'footer') {
        echo '<input type=submit name="delete" value="Supprimer" />
             </fieldset>';
      }
      else {
        echo '<input type=submit name="saveAll" value="Sauvegarder sur toutes les pages" />
             </fieldset>';
      }
      echo '</form>';
    }
    function textarea($input, $name, $rows, $cols) {
        return '<textarea name="'.$name.'" rows='.$rows.' cols='.$cols.'>'.$input.'</textarea>';
    }
    include('admin/php.php');
/*----------------------------------------------------VIEW-------------------------------------------------------------*/
    echo '
        <!doctype html>
        <html lang='.$web['lang'].'>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <meta name="description" content="'.$web['description'].'" />
                <title>'.$web['description'].' - '.$web['title'].'</title>
                <style>
                '.$web['style'].'
                </style>
                <style>
                    #section_title {display: none;}
                </style>
            </head>
            <body>
                <div id="allPage">
                <header>';
    if(isset($_SESSION['owner']) AND $_SESSION['owner']) {
        if(!$_SESSION['editor']) {
            echo '<a href="root.php?action=edit&page=web&code='.$page.'&page='.$subpage.'">
                       <img src="img/edit.png" alt="edit" height=30 width=30 class="floatRight"/>
                   </a>';
        }
        else {
            echo '<a href="root.php?action=save&page=web&code='.$page.'&page='.$subpage.'"><div id=foot_left>
                      <img src="img/save.png" alt="edit" height=30 width=30 class="floatRight" />
                   </a>';
        }
    }
    if($_SESSION['editor']) {
        /*-----STYLESHEETS-----*/
        echo '<form method=post action="web.php?code='.$page.'&page='.$subpage.'">
              <fieldset>
              <div class= center>
                  <label>Style du site : </label>
                  <select name="update">';
        $query = $pdo->query('SELECT name FROM themes ORDER BY name');
        while($queryFetch = $query->fetch()) {
            echo '
                <option value="'.$queryFetch['name'].'"
            ';
            if($web['style_name'] == $queryFetch['name']) {
                echo ' selected ';
            }
            echo '>'.$queryFetch['name'].'</option>
            ';
        }
        $query->closeCursor();
        echo '</select>
               <input name="keyword" type="hidden" value="'.$page.'" />
               <input name="change_style" type=submit value="Changer le style" />
               <a href="css_editor.php?code='.$page.'&page='.$subpage.'" target="_blanc">
                   <input class="button" type=button name="editCSS" value="Style" />
               </a>';
        if(isset($_SESSION['status']) AND $_SESSION['status'] == 'root') {
            echo ' <a href="php_editor.php" target="_blanc">
                   <input class="button" type=button name="editPHP" value="Controller" />
                   </a>
                   <a href="script_editor.php?tmp='.$page.'" target="_blanc">
                   <input class="button" type=button name="editSCRIPT" value="Script" />
                   </a>';
        }
        echo '</div>
               </fieldset>
               <fieldset>
               <label>Accès au site : </label>
                <select name="chmod">
                    <option value="public" ';
                    if($web['chmod'] == 'public') {
                        echo 'selected';
                    }
                    echo '>Public</option>
                    <option value="user" ';
                    if($web['chmod'] == 'user') {
                        echo 'selected';
                    }
                    echo '>Nécessitant une connexion</option>
                    <option value="owner" ';
                    if($web['chmod'] == 'owner') {
                        echo 'selected';
                    }
                    echo '>Privée</option>
                </select>
                <input name="privacy" type=submit value="Enregistrer" />
               </fieldset>
            </form></br>';
        /*-------EDITOR-----*/
        echo '<div id="titlePanel">';
            textForm('title', $web, $page, $subpage);
            textForm('description', $web, $page, $subpage);
            echo '
                <form method=post action="web.php?code='.$page.'&page='.$subpage.'" class="textForm" id="goPage">
                    <fieldset>
                        <select name="direction">
             ';
             $query = $pdo->prepare('SELECT * FROM site WHERE keyword = :keyword AND name = "description"');
             $query->execute(array('keyword' => $page));
             while($queryPageArray = $query->fetch()) {
                echo '<option value="'.$queryPageArray['page'].'" ';
                if($queryPageArray['page'] == $subpage) {
                    echo 'selected';
                }
                echo '>'.$queryPageArray['content'];
                if($queryPageArray['page'] == $web['home']) {
                    echo ' (Accueil)';
                }
                echo '</option>';
             }
             $query->closeCursor();
             echo '
                        </select>
                        <input name="go_to_page" type=submit value="Voir cette page" />
                        <input name="newHome" type=submit value="Définir comme accueil" />
                    </fieldset>
                </form>
            ';
        echo '</div>';
        articleForm('nav', $web, $page, $subpage);
        echo '</header><section>';
        for($i = 1; $i <= $sectionNumber; $i++) {
            if(in_array('section'.$i, $functionArray)) {
                echo '<div class="articleForm" id="functionEdit'.$i.'">';
                textForm('section'.$i, $web, $page, $subpage);
                echo '</div>';
            }
            else {
                echo '<div class="articleForm" id="sectionEdit'.$i.'">';
                articleForm('section'.$i, $web, $page, $subpage);
                echo '</div>';
            }
        }
        if(isset($web['footer']) AND $web['footer'] != 'disable') {
            $footer = 'Désactiver';
        }
        else {
            $footer = 'Activer';
        }
        echo '
            <form method=post action="web.php?code='.$page.'&page='.$subpage.'" id="footPanel">
                <input type=submit name="newSection" class="newButton" id="newSectionButton" value="Nouvel article" />
                <input type=submit name="newFunction" class="newButton" id="newFunctionButton" value="Nouvelle variable" />
                <input type=submit name="enableFooter" class="newButton" id="newSectionButton" value="'.$footer.' le pieds de page" />
                <input type=submit name="newPage" class="newButton" id="newPageButton" value="Nouvelle page" />
                <input type=submit name="deletePage" class="newButton" id="newPageButton" value="Supprimer la page" />
            </form>
        ';
        if(isset($web['footer']) AND $web['footer'] != 'disable') {
            echo '<div id="editFooter">';
            articleForm('footer', $web, $page, $subpage);
            echo '</div>';
        }
        echo '</section>';
    }
    else {
        echo '<div id="identity"><a href="web.php?code='.$page.'"><h1 id="title">'.$web['title'].'</h1></a>';
            //echo $phpEditForm;
        echo '<p id="description">'.$web['description'].'</p></div>
                <nav>'.$web['nav'].'</nav>
           </header>
           <section><h2 id="section_title">Website\'s sections</h2>';
        for($i = 1; $i <= $sectionNumber; $i++) {
            if(in_array('section'.$i, $functionArray)) {
                echo '<div id="function'.$i.'">'.$web[$web['section'.$i]].'</div>';
            }
            else {
                echo '
                    <article id="section'.$i.'">'.$web['section'.$i].'</article>
                ';
            }
        }
        if(isset($web['editPublic']) AND $web['editPublic'] == 'enable') {
            echo '<div class="articleForm" id="sectionEdit'.$i.'">';
            articleForm('section'.$i, $web, $page, $subpage);
            echo '</div>';
        }
        echo '</section>
        </div>';
        if(isset($web['footer']) AND $web['footer'] != 'disable') {
            echo '
                <footer>
                    '.$web['footer'].'
                </footer>
            ';
        }
    }
    if(isset($web['script'])) {
        echo $web['script']; 
    }
          echo '</body>
        </html>
    ';
?>