<?php
/*---------------------------INIT VAR----------------------------*/
    $newSite['legend'] = 'Nouveau site';
    $newSite['title'] = 'Nom du site';
    $newSite['description'] = 'Titre de la page';
    $newSite['submit'] = 'Créer un site';
    $i = 0;
    $k = 0;
/*---------------------------SITE--------------------------------*/
    if(isset($_POST['delete']) AND isset($_POST['site'])) {
        $query = $pdo->prepare('DELETE FROM site WHERE keyword = :keyword');
        $query->execute(array('keyword' => $_POST['site']));
        $query->closeCursor();
        header('Location '.$page.'.php');
    }
    function newSite($newSite) {
        echo '
            <form method=post action="site.php" class=center>
                <p>
                    <legend>
                        '.$newsite['legend'].'
                    </legend>
                    <input name="title" type=text placeholder="'.$newSite['title'].'" required />
                    <input name="description" type=text placeholder="'.$newSite['description'].'" required />
                    <input name="new_site" type=submit value="'.$newSite['submit'].'" />
                </p>
            </form>
        ';
    }
/*---------------------------SECTION-----------------------------*/
    if($page == 'site' AND isset($_POST['title']) AND isset($_POST['description']) AND isset($_POST['new_site'])) {
        $new = $pdo->query('SELECT keyword FROM site ORDER BY keyword DESC');
        $newTransit = $new->fetch();
        $keyword = $newTransit['keyword'];
        $keyword++;
        $new = $pdo->prepare('INSERT INTO site(keyword, owner, name, content) VALUE(:keyword, :owner, :name, :content)');
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'title',
            'content' => $_POST['title']
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'description',
            'content' => $_POST['description']
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'date',
            'content' => date('d-m-Y')
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'time',
            'content' => date('H:i')
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'lang',
            'content' => 'fr'
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'style_name',
            'content' => 'standard'
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'chmod',
            'content' => 'public'
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'home',
            'content' => 0
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'nav',
            'content' => 
'<!-- Barre de navigation
    <ul>
        <li></li>
        ...
    </ul>
-->'
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'footer',
            'content' => 'disable'
        ));
        $new->execute(array(
            'keyword' => $keyword,
            'owner' => $_SESSION['masterkey'],
            'name' => 'script',
            'content' => ''
        ));
        $new->closeCursor();
        header('Location: '.$page.'.php?site=true');
    }
    else {
        $query = $pdo->prepare('SELECT * FROM site WHERE owner = :owner ORDER BY keyword DESC');
        $query->execute(array('owner' => $_SESSION['masterkey']));
        $keywordSite = array();
        while($queryFetch = $query->fetch()) {
            if($queryFetch['name'] == 'title') {
                if(!in_array($queryFetch['keyword'], $keywordSite)) {
                    $titleSite[$i] = $queryFetch['content'];
                    $keywordSite[$i] = $queryFetch['keyword'];
                    $i++;
                }
            }
            if($queryFetch['name'] == 'description') {
                $description[$k] = $queryFetch['content'];
                $k++;
            }
        }
        $query->closeCursor();
        if($i == 0) {
            echo '<p>Vous n\'avez pas encore créé de site web. Commencez dès maintenant si vous le souhaitez.</p>';
        }
        else {
            if(isset($_GET['site'])) {
                echo '<h3>Vous venez de créer un site, félicitation.</h3>';
            }
            else {
                echo '<p>Vous avez déjà créé '.$i.' site';
                if($i >> 1) {
                    echo 's';
                }
                echo ' avec notre service.<p>';
            }
        }
        echo '<div class="wrapper">
              <ul id="filter-container" class="grid cs-style-1">
                <li class="home">';
        for($j = 0; $j < $i; $j++) {
            echo '
                    <figure>
                        <section>
                            <article>
                                <fieldset>
                                    <h3>'.$titleSite[$j].'</h3>
                                    <p>'.$description[$j].'</p>
                                    <form method=post action="'.$page.'.php">
                                        <input name="site" type="hidden" value="'.$keywordSite[$j].'" />
                                        <a href="web.php?code='.$keywordSite[$j].'" target="_blank">
                                            <input name="access" type=button value="Accéder au site" />
                                        </a>';
                                        if($keywordSite[$j] != 0) {
                                           echo '<input name="delete" type=submit value="Supprimer" />';
                                        }
                                   echo '</form>
                                </fieldset>
                            </article>
                        </section>
                    </figure>
                ';
        }
        echo '</li></ul></div></div></br></br>';
        newSite($newSite);
    }
?>