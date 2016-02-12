<?php
    if(isset($_GET['action']) AND isset($_GET['page'])) {
        if(isset($_GET['code']) AND isset($_GET['page'])) {
            if($_GET['action'] == 'edit') {
                header('Location: web.php?code='.$_GET['code'].'&page='.$_GET['page'].'&editor=true');
            }
            if($_GET['action'] == 'save') {
                header('Location: web.php?code='.$_GET['code'].'&page='.$_GET['page'].'&editor=false');
            }
            if($_GET['action'] == 'logout') {
                header('Location: web.php?code='.$_GET['code'].'&page='.$_GET['page'].'&logout=true');
            }
        }
        else {
            if($_GET['action'] == 'edit') {
                header('Location: '.$_GET['page'].'.php?editor=true');
            }
            if($_GET['action'] == 'save') {
                header('Location: '.$_GET['page'].'.php?editor=false');
            }
            if($_GET['action'] == 'logout') {
                header('Location: '.$_GET['page'].'.php?logout=true');
            }
        }
    }
?>