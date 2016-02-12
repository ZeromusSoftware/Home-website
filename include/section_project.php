<?php
  if(isset($_SESSION['admin']) OR isset($_SESSION['user'])) {
    echo '<section>
                <h3>Accédez aux différents classements de notre système</h3>
                <article class="loginBox">
                  <form method="POST" action="project.php">
                    <p>
                      <label>Nom de la compétition :</label>
                      <select name="contest">
                        <option value="null">[ Indifferent ]</option>';
    $timesID = $pdo->query('SELECT id FROM score ORDER BY id DESC');
    $timesNumber = $timesID->fetch();
    $timesID->closeCursor();
    /*-----------------CONTEST SELECT----------------------------------*/
    $timesAll = $pdo->query('SELECT * FROM score ORDER BY contest');
    $i = 0;
    while($timesDB = $timesAll->fetch()) {
        $contestSearch[$i] = $timesDB['contest'];
        $i++;
    }
    $timesAll->closeCursor();
    for($i = 0; $i < $timesNumber[0]; $i++) {
        if(!in_array($contestSearch[$i],$testArray)) {
            echo '<option value="'.$contestSearch[$i].'"';
            if(isset($_POST['contest']) AND $_POST['contest'] == $contestSearch[$i]) {
                echo 'selected';
            }
            echo '>'.$contestSearch[$i].'</option>';
            $testArray[$i] = $contestSearch[$i];
        }
    }
    /*--------------------------------------------------------------*/
    echo ' </select></br>
                      <label>Nom de l\'équipe :</label>
                      <select name="team">
                        <option value="null">[ Indifferent ]</option>';
    /*-----------------TEAM SELECT----------------------------------*/
    $timesAll = $pdo->query('SELECT * FROM score ORDER BY team');
    $i = 0;
    while($timesDB = $timesAll->fetch()) {
        $contestSearch[$i] = $timesDB['team'];
        $i++;
    }
    $timesAll->closeCursor();
    for($i = 0; $i < $timesNumber[0]; $i++) {
        if(!in_array($contestSearch[$i],$testArray)) {
            echo '<option value="'.$contestSearch[$i].'"';
            if(isset($_POST['team']) AND $_POST['team'] == $contestSearch[$i]) {
                echo 'selected';
            }
            echo '>'.$contestSearch[$i].'</option>';
            $testArray[$i] = $contestSearch[$i];
        }
    }
    /*--------------------------------------------------------------*/
    echo '</select></br>';
                      /*'<label>Type de course :</label>
                      <input type="radio" name="type" value="all" id="all" /> 
                      <label for="all">Tout</label>
                      <input type="radio" name="type" value="auto" id="auto" /> 
                      <label for="auto">Automatique</label>
                      <input type="radio" name="type" value="man" id="man" />
                      <label for="man">Manuelle</label>';*/
                echo '</br>
                      <input type=hidden name="connect" value="" />
                      <input type=hidden name="search" />
                      <input type=hidden name="signin" />
                      <input type=submit class="blueButton" name="search" value="';
                      if(isset($_POST['search'])) {
                      echo 'Relancez';
                      }
                      else {
                        echo 'Lancez';
                      }
                      echo' une recherche" /></br>
                    </p>
                  </form>';
    echo  '</article></br></br>';
    if(isset($_POST['search']) AND $_POST['search'] == 'empty') {
        echo '<article class="loginBox">
               <p>Aucun résultat accessible pour cette recherche.</p>
               </article>'; 
    }
    if(isset($_POST['search']) AND $_POST['search'] == 'found') {
        if(isset($_POST['result']) AND $_POST['result'] == 'contest') {
            $timesAll = $pdo->prepare('SELECT * FROM score WHERE contest = :contest ORDER BY global');
            $timesAll->execute(array('contest' => $_POST['contest']));
        }
        if(isset($_POST['result']) AND $_POST['result'] == 'team') {
            $timesAll = $pdo->prepare('SELECT * FROM score WHERE team = :team ORDER BY global');
            $timesAll->execute(array('team' => $_POST['team']));
        }
        if(isset($_POST['result']) AND $_POST['result'] == 'all') {
            $timesAll = $pdo->prepare('SELECT * FROM score WHERE contest = :contest AND team = :team ORDER BY global');
            $timesAll->execute(array('contest' => $_POST['contest'],
                                      'team' => $_POST['team']));
        }
        $i = 0;
        $resultNumber = 0;
        while($timesDB = $timesAll->fetch()) {
                $contestResult[$i] = $timesDB['contest'];
                $teamResult[$i] = $timesDB['team'];
                $bestTimeResult[$i] = $timesDB['global'];
                $timeResult[$i] = $timesDB['global'];
                $resultNumber++;
                $i++;
        }
        $timesAll->closeCursor();
        if($resultNumber == 0) {
            echo '<article class="loginBox">
                   <p>Aucun résultat accessible pour cette recherche.</p>
                   </article>'; 
        }
        else {
            echo '<article class="loginBox"><p>
                      <table>
                        <h3>Voici les résultats de votre recherche</h3>
                            <tr>
                                <th>Classement</th>
                                <th>Compétition</th>
                                <th>Equipe</th>';
            if(isset($_POST['result']) AND $_POST['result'] == 'contest') {
                echo '<th>Meilleur temps</th>';
            }
            if(isset($_POST['result']) AND ($_POST['result'] == 'all' OR $_POST['result'] == 'team')) {
                echo '<th>Temps de course</th>';
            }
            echo '</tr>';
                $j = 0;
                $teamTestArray = array('teamTestArray');
                for($i = 0; $i < $resultNumber; $i++) {
                    if(!in_array($teamResult[$i],$teamTestArray) AND isset($_POST['result']) AND $_POST['result'] == 'contest') {
                        $j++;
                        echo '<tr>
                                    <td>'.$j.'</td>
                                    <td>'.$contestResult[$i].'</td>
                                    <td>'.$teamResult[$i].'</td>
                                    <td>'.$bestTimeResult[$i].'</td>
                               </tr>';
                        $teamTestArray[$i] = $teamResult[$i];
                    }
                    if(isset($_POST['result']) AND ($_POST['result'] == 'team' OR $_POST['result'] == 'all')) {
                        $j++;
                        echo '<tr>
                                    <td>'.$j.'</td>
                                    <td>'.$contestResult[$i].'</td>
                                    <td>'.$teamResult[$i].'</td>
                                    <td>'.$timeResult[$i].'</td>
                               </tr>';
                        $teamTestArray[$i] = $teamResult[$i];
                    }
                }
        
            echo '</table></p></article>';
        }
    }
    echo '</section>';
  }
  else {
    header('Location: login.php?from=project');
  }
?>
