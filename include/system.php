<?php
    if(isset($_SESSION['user']) AND $_SESSION['user']) {
        if($page == 'site') {
            $cpdo = 'SELECT site FROM accounts WHERE login = :login';
        }
        if($page == 'stats') {
            $cpdo = 'SELECT stats FROM accounts WHERE login = :login';
        }
        if($page == 'work') {
            $cpdo = 'SELECT work FROM accounts WHERE login = :login';
        }
        $check = $pdo->prepare($cpdo);
        $check->execute(array('login' => $_SESSION['login']));
        $checkArray = $check->fetch();
        if($checkArray[$page] == 'true') {
            if(isset($_POST['goodbye'])) {
                if($page == 'site') {
                    $spdo = 'UPDATE accounts SET site = "false" WHERE login = :login';
                }
                if($page == 'stats') {
                    $spdo = 'UPDATE accounts SET stats = "false" WHERE login = :login';
                }
                if($page == 'work') {
                    $spdo = 'UPDATE accounts SET work = "false" WHERE login = :login';
                }
                $subscribe = $pdo->prepare($spdo);
                $subscribe->execute(array('login' => $_SESSION['login']));
                $subscribe->closeCursor();
                echo '
                    <p>Vous vous êtes désinscrit du service '.$page_title.'.
                    <form method=post action="'.$page.'.php" class=center>
                        <input type=submit name=new value="S\'inscrire à nouveau" />
                    </form>
                    </p>
                ';
            }
            else {
                echo '<p>';
                if(isset($_GET['new']) AND $_GET['new'] == 'true') {
                    echo 'Vous êtes désormais inscrit au service '.$page_title.'. Félicitation.';
                }
                else {
                    echo 'Vous êtes déjà bien inscrit au service '.$page_title.'.';
                }
                echo '
                    </br>
                        Il est en développement, il vous faut encore patienter un peu pour avoir toutes les fonctionnalités.
                    <form method=post action="'.$page.'.php" class=center>
                        <input type=submit name=goodbye value="Se désinscrire" />
                    </form></p>
                ';
                separator();
/*-----------------------------------------------------------------------------*/
                include('include/section_'.$page.'.php');
/*-----------------------------------------------------------------------------*/
            }
        }
        else {
            if(isset($_POST['new'])) {
                if($page == 'site') {
                    $spdo = 'UPDATE accounts SET site = "true" WHERE login = :login';
                }
                if($page == 'stats') {
                    $spdo = 'UPDATE accounts SET stats = "true" WHERE login = :login';
                }
                if($page == 'work') {
                    $spdo = 'UPDATE accounts SET work = "true" WHERE login = :login';
                }
                $subscribe = $pdo->prepare($spdo);
                $subscribe->execute(array('login' => $_SESSION['login']));
                $subscribe->closeCursor();
                header('Location: '.$page.'.php?new=true');
            }
            else {
                echo '
                    <p>Si vous désirez réellement souscrire au service '.$page_title.', il vous suffit de cliquer sur le bouton suivant :
                    <form method=post action="'.$page.'.php" class=center>
                        <input type=submit name=new value="Je m\'inscris" />
                    </form></p>
                ';
            }
        }
        $check->closeCursor();
    }
    else {
        header('Location: login.php?from='.$page.'');
    }
?>
