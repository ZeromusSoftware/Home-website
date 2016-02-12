<?php
    if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'editor') {
        TextForm($website_welcome_members0, $page);
        echo '$accounts_number_ironhawk ';
        TextForm($website_welcome_members1, $page);
        separator();
        TeamForm($paradoxe01, 'name', $page);
        TeamForm($paradoxe01, 'fonction', $page);
        TeamForm($paradoxe01, 'description', $page);
        separator();
        TeamForm($wuwu, 'name', $page);
        TeamForm($wuwu, 'fonction', $page);
        TeamForm($wuwu, 'description', $page);
        separator();
        TeamForm($pioupiou, 'name', $page);
        TeamForm($pioupiou, 'fonction', $page);
        TeamForm($pioupiou, 'description', $page);
        separator();
        TeamForm($joh, 'name', $page);
        TeamForm($joh, 'fonction', $page);
        TeamForm($joh, 'description', $page);
    }
    else {
        /*------------MEMBER ACTIVITY-------------*/
        if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'standard') {
            $activity = 0;
            $actif_member = $pdo->query('SELECT actif FROM accounts WHERE actif = 1');
            while($i = $actif_member->fetch()) {
                $activity++;
            }
            $actif_member->closeCursor();
            echo '<h1>[ Info admin : Il y a '.$activity.' membres actifs. ]</h1>';
        }
        /*----------------------------------------*/
        echo '<h1>'.$website_welcome_members0['content'].' '.$accounts_number_ironhawk.' '.$website_welcome_members1['content'].'</h1>';
        for($i = 0; $i < $accounts_number_ironhawk; $i++) {
            separator();
            echo '
                <section>
                    <article>
                        <h1>'.$name[$i].'</h1>
                        <h2>'.$fonction[$i].'</h2>
                        <p>'.$description[$i].'</p>
                    </article>
                </section>
            ';
        }
    }
?>
