<?php
//New visit
  if(isset($page) AND isset($_SERVER["REMOTE_ADDR"])) {
      if(isset($_GET['title'])) {
        $stats_key = $_GET['title'];
      }
      else {
        $stats_key = 0;
      }
      $stats = $pdo->prepare('INSERT INTO stats(keyword, date, time, page, ip)
                              VALUES(:keyword, :date, :time, :page, :ip)');
      $stats->execute(array('keyword' => $stats_key,
                             'date' => date('d-m-Y'),
                             'time' => date('H:i:s'),
                             'page' => $page,
                             'ip' => $_SERVER['REMOTE_ADDR']
                             ));
      $stats->closeCursor();
  }
//Sign IN
  if(isset($_POST['signin']) AND isset($_POST['login']) AND $_POST['login'] != NULL AND isset($_POST['password']) AND $_POST['password'] != NULL) {
    $pass = $pdo->prepare('SELECT password FROM accounts WHERE login = :login');
    $pass->execute(array('login' => $_POST['login']));
    $password = $pass->fetch();
    $pass->closeCursor();
    if($_POST['password'] == $password['password']) {
        $status = $pdo->prepare('SELECT * FROM accounts WHERE login = :login');
        $status->execute(array('login' => $_POST['login']));
        $status_admin = $status->fetch();
        $status->closeCursor();
        $_SESSION['user'] = true;
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
        if($status_admin['keyword'] == NULL) {
            $new = $pdo->query('SELECT keyword FROM accounts ORDER BY keyword DESC');
            $testKey = $new->fetch();
            $keywordMax = $testKey['keyword'];
            $keywordMax++;
            $new->closeCursor();
            $new = $pdo->prepare('UPDATE accounts SET keyword = :keyword WHERE login = :login');
            $new->execute(array(
                'keyword' => $keywordMax,
                'login' => $_SESSION['login']
            ));
            $_SESSION['masterkey'] = $keywordMax;
        }
        else {
            $_SESSION['masterkey'] = $status_admin['keyword'];
        }
        $_SESSION['status'] = $status_admin['status'];
        if($_SESSION['status'] == 'root' OR $_SESSION['status'] == 'admin') {
            $_SESSION['admin'] = 'standard';
        }
        $last = $pdo->prepare('UPDATE accounts SET actif = true, lastvisit = :lastvisit WHERE login = :login');
        $last->execute(array('lastvisit' => date('d-m-Y'),
                              'login' => $_SESSION['login']));
        $last->closeCursor();
        $stats_login = $pdo->prepare('INSERT INTO stats(keyword, date, time, ip, login)
                               VALUES(:keyword, :date, :time, :ip, :login)');
        $stats_login->execute(array(
            'keyword' => $stats_key,
            'date' => date('d-m-Y'),
            'time' => date('H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'login' => $_SESSION['masterkey']
        ));
        $stats_login->closeCursor();
        if(isset($_GET['from']) AND $_GET['from'] != 'index') {
            header('Location: '.$_GET['from'].'.php');
        }
        else {
            header('Location: web.php?code=0');
        }
        if(isset($_POST['comment']) AND $_POST['comment'] != NULL) {
            mail($email, 'Iron Hawk Comment', $_POST['comment']);
        }
    }
    else {
        $_POST['error'] = 'login';
    }
  }
  if(isset($_POST['register'])) {
    if(isset($_POST['login']) AND $_POST['login'] != NULL) {
        $log = $pdo->query('SELECT login FROM accounts ORDER BY id');
        $login = $log->fetch();
        $log->closeCursor();
        if(!in_array($_POST['login'],$login)) {
            if(isset($_POST['password']) AND $_POST['password'] != NULL AND isset($_POST['password2']) AND $_POST['password'] == $_POST['password2']) {
                if(isset($_POST['email']) AND $_POST['email'] != NULL) {
                    $emailTest = $pdo->query('SELECT email FROM accounts ORDER BY id');
                    $emailTester = $emailTest->fetch();
                    $emailTest->closeCursor();
                    if(!in_array($_POST['email'],$emailTester)) {
                        $_SESSION['login'] = $_POST['login'];
                        $_SESSION['password'] = $_POST['password'];
                        $_SESSION['email'] = $_POST['email'];
                        $mail_confirm = $url_website.'/register.php?account=activated&login='.urlencode($_SESSION['login']).'&password='.urlencode($_SESSION['password']).'&email='.urlencode($_SESSION['email']);
                        $mail_message = 'Veuillez cliquer sur le lien de confirmation afin de terminer votre inscription :
          '.$mail_confirm;
                        @mail($_SESSION['email'], $title.' Team', $mail_message);
                        $_POST['register'] = 'sentMail';
                    }
                    else {
                         $_POST['error'] = 'email already exist';
                    }
                }
                else {
                    $_POST['error'] = 'email';
                }
            }
            else {
                $_POST['error'] = 'password';
            }
        }
        else {
            $_POST['error'] = 'login already exist';
        }
    }
    else {
        $_POST['error'] = 'login';
    }
  }
  if(isset($_POST['resendMail']) AND isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
    if($page == 'login') {
        $emailTest = $pdo->query('SELECT email FROM accounts ORDER BY id');
        $emailTester = $emailTest->fetch();
        $emailTest->closeCursor();
        if(in_array($_SESSION['email'],$emailTester)) {
            $identifiants0 = $pdo->prepare('SELECT login FROM accounts WHERE email = :email');
            $identifiants0->execute(array('email' => $_SESSION['email']));
            $loginUser = $identifiants0->fetch();
            $identifiants0->closeCursor();
            $identifiants1 = $pdo->prepare('SELECT password FROM accounts WHERE email = :email');
            $identifiants1->execute(array('email' => $_SESSION['email']));
            $passwordUser = $identifiants1->fetch();
            $identifiants1->closeCursor();
            $mail_message = 'Voici les identifiants que vous aviez perdus.
                             Login : '.$loginUser['login'].'
                             Password : '.$passwordUser['password'].'';
            mail($_SESSION['email'], 'Iron Hawk Team', $mail_message);
            $_POST['register'] = 'sentMail';
            header('Location: '.$page.'.php');
        }
        else {
            header('Location: register.php');
        }
    }
    else {
        $mail_confirm = $url_website.'/register.php&amp;account=activated&amp;login='.$_SESSION['login'].'&amp;password='.$_SESSION['password'].'&amp;email='.$_SESSION['email'];
        $mail_message = 'Veuillez cliquer sur le lien de confirmation afin de terminer votre inscription :
                        '.$mail_confirm.'';
        mail($_SESSION['email'], 'Iron Hawk Team', $mail_message);
        $_POST['register'] = 'sentMail';
        header('Location: '.$page.'.php');
    }
  }
  if(isset($_GET['account']) AND $_GET['account'] == 'activated' AND isset($_GET['login']) AND isset($_GET['password']) AND isset($_GET['email'])) {
        $log = $pdo->query('SELECT login FROM accounts ORDER BY id');
        $login = $log->fetch();
        $log->closeCursor();
        if(!in_array($_GET['login'],$login)) {
            $new = $pdo->query('SELECT keyword FROM accounts ORDER BY keyword DESC');
            $testKey = $new->fetch();
            $keywordMax = $testKey['keyword'];
            $keywordMax++;
            $new->closeCursor();
            $new = $pdo->prepare('INSERT INTO accounts(keyword, login, password, email, creation, status) 
                                   VALUES(:keyword, :login, :password, :email, :creation, :status)');
            $new->execute(array(
                'keyword' => $keywordMax,
                'login' => $_GET['login'],
                'password' => $_GET['password'],
                'creation' => date('d-m-Y'),
                'email' => $_GET['email'],
                'status' => 'user'));
            $new->closeCursor();
            $_SESSION['login'] = $_GET['login'];
        }
        header('Location: login.php?register=finished');
  }
  /*---------------Log out system-----------------------*/
  if(isset($_GET['logout']) AND $_GET['logout'] == 'true') {
    $actif = $pdo->prepare('UPDATE accounts SET actif = false WHERE login = :login');
    $actif->execute(array('login' => $_SESSION['login']));
    $actif->closeCursor();
    session_destroy();
    session_start();
    header('Location: '.$page.'.php');
  }
  /*---------------Enable editor mode------------------------*/
  if(isset($_GET['editor']) AND $_GET['editor'] == 'true' AND isset($_SESSION['status'])) {
    if($_SESSION['status'] == 'root' OR $_SESSION['status'] == 'admin') {
        $_SESSION['admin'] = 'editor';
        header('Location: '.$page.'.php');
    }
    else {
        $_SESSION['admin'] = NULL;
        header('Location: '.$page.'.php');
    }
  }
  /*----------------Disable editor mode--------------------*/
  if(isset($_GET['editor']) AND $_GET['editor'] == 'false') {
    $_SESSION['admin'] = 'standard';
    header('Location: '.$page.'.php');
  }
  /*---------------Member redirection----------------------*/
  if(isset($_POST['members']) AND $_POST['members']) {
    header('Location: members.php');
  }
  /*---------------Project redirection---------------------*/
  if(isset($_POST['project']) AND $_POST['project']) {
    header('Location: project.php');
  }
  /*---------------Login function--------------------------*/
  function Login($input, $lang, $page) {
    if(isset($_SESSION['admin']) AND $_SESSION['admin'] == true) {
      echo '<form method=post action="index.php">
              <p>';
      /*echo    '<fieldset>
                  <legend>Option</legend>
                  <label for="description">Description : </label>
                  <input name="description" id="description" type=text size=40 maxlength=40 placeholder="'.$input['content'].'" /></br>
                </fieldset>
      */
      echo      '<input type=submit name="logout" value="Se déconnecter" />
              </p>
            </form>';
    }
    else {
      echo '<div id="connectBox">
             <table>
                <tr>
                   <td><a href="register.php" class=".button">S\'inscrire </a></td>
                   <td class="separator"></td>
                   <td><a href="login.php?from='.$page.'" class=".button"> Se connecter</a></td>
                </tr>
             </table>
            </div>';
    }
  }
  $website_style = array( $website_style1['content'],
                          $website_style2['content'],
                          $website_style3['content'],
                          $website_style4['content'],
                          $website_style5['content'],
                          $website_style6['content'],
                          $website_style7['content'] );
  /*
  $website_joliplume_header = array(  $website_joliplume1['content'],
                                      $website_joliplume2['content'],
                                      $website_joliplume3['content'],
                                      $website_joliplume4['content'],
                                      $website_joliplume5['content']);
  */
  if($page == 'index' OR $page == 'iron_system') {
  $website_nav_section = array( $website_nav_section1['content'], 
                                $website_nav_section2['content'], 
                                $website_nav_section3['content'] );
  $website_section_subtitle = array( $website_section_subtitle1['content'], 
                                     $website_section_subtitle2['content'], 
                                     $website_section_subtitle3['content'] );
  $website_section_articleO = array( $website_section1_article0['content'],
                                     $website_section2_article0['content'],
                                     $website_section3_article0['content'],
                                     $website_section4_article0['content'],
                                     $website_section5_article0['content'],
                                     $website_section6_article0['content'],
                                     $website_section7_article0['content'],
                                     $website_section8_article0['content'],
                                     $website_section9_article0['content'],
                                     $website_section10_article0['content'],
                                     $website_section11_article0['content'],
                                     $website_section12_article0['content']
                                     );
  $website_section_article1 = array( $website_section1_article1['content'],
                                     $website_section2_article1['content'],
                                     $website_section3_article1['content'],
                                     $website_section4_article1['content'],
                                     $website_section5_article1['content'],
                                     $website_section6_article1['content'],
                                     $website_section7_article1['content'],
                                     $website_section8_article1['content'],
                                     $website_section9_article1['content'],
                                     $website_section10_article1['content'],
                                     $website_section11_article1['content'],
                                     $website_section12_article1['content']
                                     );
  }
  if($page == 'steel_dragon') {
  $website_section_articleO = array( $website_steel_dragon_section0_article0['content'],
                                     $website_steel_dragon_section1_article0['content'],
                                     $website_steel_dragon_section2_article0['content']
                                     );
  $website_section_article1 = array( $website_steel_dragon_section0_article1['content'],
                                     $website_steel_dragon_section1_article1['content'],
                                     $website_steel_dragon_section2_article1['content']
                                     );
  }
  if($page == 'plumeos') {
  $website_section_articleO = array( $website_plumeos_section0_article0['content'],
                                     $website_plumeos_section1_article0['content'],
                                     $website_plumeos_section2_article0['content']
                                     );
  $website_section_article1 = array( $website_plumeos_section0_article1['content'],
                                     $website_plumeos_section1_article1['content'],
                                     $website_plumeos_section2_article1['content']
                                     );
  }
  if($page == 'falcon_eye') {
  $website_section_articleO = array( $website_falcon_eye_section0_article0['content'],
                                     $website_falcon_eye_section1_article0['content'],
                                     $website_falcon_eye_section2_article0['content']
                                     );
  $website_section_article1 = array( $website_falcon_eye_section0_article1['content'],
                                     $website_falcon_eye_section1_article1['content'],
                                     $website_falcon_eye_section2_article1['content']
                                     );
  }
  if($page == 'genuine_site') {
  $website_section_articleO = array( $website_genuine_site_section0_article0['content'],
                                     $website_genuine_site_section1_article0['content'],
                                     $website_genuine_site_section2_article0['content']
                                     );
  $website_section_article1 = array( $website_genuine_site_section0_article1['content'],
                                     $website_genuine_site_section1_article1['content'],
                                     $website_genuine_site_section2_article1['content']
                                     );
  }
  if($page == 'app') {
  $website_section_articleO = array( $website_app_section0_article0['content'],
                                     $website_app_section1_article0['content'],
                                     $website_app_section2_article0['content']
                                     );
  $website_section_article1 = array( $website_app_section0_article1['content'],
                                     $website_app_section1_article1['content'],
                                     $website_app_section2_article1['content']
                                     );
  }
  /*function AddIdeaForm($input, $page) {
      echo '<form method=post action="'.$page.'.php" id="AddJoliForm"></br>
              <fieldset>
                <legend><h3>Nouvelle idée</h3></legend>
                <label for="title">Nom : </label></br><input name="title" id="title" size=33 type=text required /></br>
                <label for="subtitle">Description : </label></br><input name="subtitle" id="subtitle" size=33 type=text required /></br>
                <label for="article">Précision : </label></br><input name="article" id="article" size=33 type=text /></br>
                <label for="link">Adresse email : </label></br><input name="link" id="link" size=33 type=text required /></br></br>
                <input name="AddJoli" type="hidden" value="" />
                <div class= center>
                  <select name="filter">
                    <option value="1">'.$input[1].'</option>
                    <option value="2">'.$input[2].'</option>
                    <option value="3">'.$input[3].'</option>
                    <option value="4">'.$input[4].'</option>
                  </select>
                  <input type=submit value="Partager" />
                </div>
              </fieldset>
            </form>';
  }*/
  function separator() {
        echo '<img class="center" src="img/div.png" alt="divisor" />';
   }
  /*if(isset($_POST['AddJoli'])) {
     //mail($email, 'Coffeeroot', 'Une nouvelle idée a été partagée :-)');
      $insert = $pdo->prepare('INSERT INTO ideas(title, subtitle, article, link, filter) 
                               VALUES(:title, :subtitle, :article, :link, :filter)');
      $insert->execute(array('title' => $_POST['title'],
                             'subtitle' => $_POST['subtitle'],
                             'article' => $_POST['article'],
                             'link' => $_POST['link'],
                             'filter' => $_POST['filter']));
      $insert->closeCursor();
      header('Location: index.php');
    }*/
    /*----------------------PROJECT AREA-----------------*/
  if($page == 'project') {
    if(isset($_GET['date']) AND isset($_GET['contest']) AND isset($_GET['team']) AND isset($_GET['press_button']) AND isset($_GET['auto_race']) AND isset($_GET['start_arrival']) AND isset($_GET['reaction']) AND isset($_GET['timeout']) AND isset($_GET['canceled']) AND isset($_GET['global'])) {
        //$borgne = true;
        $times = $pdo->prepare('INSERT INTO score(date, contest, team, press_button, auto_race, start_arrival, reaction, timeout, canceled, global) 
                               VALUES(:date,
                                      :contest,
                                      :team,
                                      :press_button,
                                      :auto_race,
                                      :start_arrival,
                                      :reaction,
                                      :timeout,
                                      :canceled,
                                      :global)');
        $times->execute(array(
                            'date' => $_GET['date'],
                            'contest' => $_GET['contest'],
                            'team' => $_GET['team'],
                            'press_button' => $_GET['press_button'],
                            'auto_race' => $_GET['auto_race'],
                            'start_arrival' => $_GET['start_arrival'],
                            'reaction' => $_GET['reaction'],
                            'timeout' => $_GET['timeout'],
                            'canceled' => $_GET['canceled'],
                            'global' => $_GET['global']));
        $times->closeCursor();
    }
    if(isset($_SESSION['user']) AND $_SESSION['user']) {
        if(isset($_POST['search']) AND isset($_POST['contest']) AND isset($_POST['team'])) {
            if($_POST['contest'] == 'null' AND $_POST['team'] == 'null') {
                $_POST['search'] = 'empty';
            }
            else {
                if($_POST['contest'] == 'null') {
                    $_POST['result'] = 'team';
                }
                if($_POST['team'] == 'null') {
                    $_POST['result'] = 'contest';
                }
                if($_POST['contest'] != 'null' AND $_POST['team'] != 'null') {
                    $_POST['result'] = 'all';
                }
                if(isset($_POST['type'])) {
                    if($_POST['type'] == 'all') {
                        $_POST['resultType'] = 'all';
                    }
                    if($_POST['type'] == 'auto') {
                        $_POST['resultType'] = 'auto';
                    }
                    if($_POST['type'] == 'man') {
                        $_POST['resultType'] = 'man';
                    }
                }
                $_POST['search'] = 'found';
            }
        }
    }
  }
    /*---------------------ADMIN AREA-----------------*/
  if(isset($_SESSION['admin']) AND $_SESSION['admin']) {
    if(isset($_POST['submit'])) {
        if(isset($_POST['editCSS'])) {
            header('Location: css_editor.php');
        }
        else {
            if(isset($_SESSION['dowgs']) AND $_SESSION['dowgs']) {
                $update = $pdo->prepare('UPDATE dowgs SET content = :update WHERE name = :submit');
            }
            else {
                if($_SESSION['lang'] == 'fr') {
                    $update = $pdo->prepare('UPDATE root SET content = :update WHERE name = :submit');
                }
                if($_SESSION['lang'] == 'en') {
                    $update = $pdo->prepare('UPDATE rooten SET content = :update WHERE name = :submit');
                }
            }
          $update->execute(array('update' => $_POST['update'],
                                 'submit' => $_POST['submit']));
          $update->closeCursor();
          header('Location: '.$page.'.php');
      }
    }
    if(isset($_POST['submitTeam']) AND isset($_POST['inputTeam'])) {
        if($_POST['inputTeam'] == 'name') {
            $updateTeam = $pdo->prepare('UPDATE accounts SET name = :updateteam WHERE login = :submitteam');
        }
        if($_POST['inputTeam'] == 'fonction') {
            $updateTeam = $pdo->prepare('UPDATE accounts SET fonction = :updateteam WHERE login = :submitteam');
        }
        if($_POST['inputTeam'] == 'description') {
            $updateTeam = $pdo->prepare('UPDATE accounts SET description = :updateteam WHERE login = :submitteam');
        }
        $updateTeam->execute(array('updateteam' => $_POST['updateTeam'],
                                    'submitteam' => $_POST['submitTeam']));
        $updateTeam->closeCursor();
        header('Location: '.$page.'.php?lang='.$_SESSION['lang'].'');
    }
    /*
    if(isset($_POST['JoliHeader'])) {
      $update = $pdo->prepare('UPDATE root SET content = :update WHERE name = :name');
      $update->execute(array('update' => $_POST['JoliHeader1'],
                             'name' => 'website_joliplume1'));
      $update->execute(array('update' => $_POST['JoliHeader2'],
                             'name' => 'website_joliplume2'));
      $update->execute(array('update' => $_POST['JoliHeader3'],
                             'name' => 'website_joliplume3'));
      $update->execute(array('update' => $_POST['JoliHeader4'],
                             'name' => 'website_joliplume4'));
      $update->execute(array('update' => $_POST['JoliHeader5'],
                             'name' => 'website_joliplume5'));
      $update->closeCursor();
      header('Location: '.$page.'.php');
    }
    if(isset($_POST['UpdateJoli'])) {
      $update = $pdo->prepare('UPDATE ideas SET title = :title,
                                                    subtitle = :subtitle,
                                                    article = :article,
                                                    link = :link,
                                                    filter = :filter
                                                    WHERE id = :name');
      $update->execute(array('title' => $_POST['title'],
                             'subtitle' => $_POST['subtitle'],
                             'article' => $_POST['article'],
                             'link' => $_POST['link'],
                             'filter' => $_POST['filter'],
                             'name' => $max-$_POST['id']));
      $update->closeCursor();
      header('Location: index.php');
    }*/
    /*if(isset($_POST['AddJoli'])) {
      $insert = $pdo->prepare('INSERT INTO ideas(title, subtitle, article, link, button, filter) 
                               VALUES(:title, :subtitle, :article, :link, :button, :filter)');
      $insert->execute(array('title' => $_POST['title'],
                             'subtitle' => $_POST['subtitle'],
                             'article' => $_POST['article'],
                             'link' => $_POST['link'],
                             'button' => $_POST['button'],
                             'filter' => $_POST['filter']));
      $insert->closeCursor();
      header('Location: index.php');
    }*/
    /*if(isset($_POST['delete'])) {
      $delete = $pdo->prepare('DELETE FROM ideas WHERE id = :id');
      $delete->execute(array('id' => $max-$_POST['id']));
      $delete->closeCursor();
      header('Location: index.php');
    }*/
    /*$update = $pdo->prepare('UPDATE joliplume SET id = id+1 WHERE id > :id');
      $update->execute(array('id' => $max-$_POST['id']));
      $update->closeCursor();
    */
    function TextForm($input, $page) {
      $size = strlen($input['content']);
      echo '<form method=post action="'.$page.'.php">
              <input  name="update" class="admin" size='.$size.' id="'.$input['name'].'" type=text value="'.$input['content'].'" />
              <input name="submit" type="hidden" value="'.$input['name'].'" />
              <input class="admin" type=submit value="" />
            </form>';
    }
    function SelectForm($input, $page) {
      $size = strlen($input['content']);
      echo '<form method=post action="'.$page.'.php">
              <fieldset>
              <div class= center>
                  <select name="update">
                    <option value="belleplume"';
                    /*for() {
                    
                    }*/
                    if($input['content'] == "belleplume") {
                      echo 'selected';
                    }
                    echo '>BellePlume</option>>)
                    <option value="coffeeroot"';
                    if($input['content'] == "coffeeroot") {
                      echo 'selected';
                    }
                    echo '>Cause café</option>
                    <option value="aidiz"';
                    if($input['content'] == "aidiz") {
                      echo 'selected';
                    }
                    echo '>Adiz</option>
                    <option value="dowgs"';
                    if($input['content'] == "dowgs") {
                      echo 'selected';
                    }
                    echo '>Dowgs</option>
                  </select>
                  <input name="submit" type="hidden" value="'.$input['name'].'" />
                  <input class="button" type=submit value="Changer le style" />
                  <a href="css_editor.php" target="_blanc">
                    <input class="button" type=button name="editCSS" value="Editez le code CSS" />
                  </a>
                </div>
                </fieldset>
            </form>';
    }
    function ArticleForm($input, $page) {
      echo '<form method=post action="'.$page.'.php">
              <textarea name="update" id="'.$input['name'].'" rows=10 cols=100>'.$input['content'].'</textarea></br>
              <input name="submit" type="hidden" value="'.$input['name'].'" />
              <input type=submit />
            </form>';
    }
    function TeamForm($login, $input, $page) {
        echo '
            <form method=post action="'.$page.'.php?lang='.$_SESSION['lang'].'">
                <textarea name="updateTeam" rows=10 cols=100>'.$login[$input].'</textarea></br>
                <input name="inputTeam" type="hidden" value="'.$input.'" />
                <input name="submitTeam" type="hidden" value="'.$login['login'].'" />
                <input type=submit />
            </form>
        ';
    }
    /*
    function AddJoliForm($page) {
      echo '<form method=post action="'.$page.'.php" id="AddJoliForm">
              <fieldset>
                <legend>Add a news</legend></br>
                <label for="title">Title : </label><input name="title" id="title" size=25 type=text required /></br></br>
                <label for="subtitle">Subtitle : </label><input name="subtitle" id="subtitle" size=20 type=text required /></br></br>
                <label for="article">Article : </label><input name="article" id="article" size=22 type=text required /></br></br>
                <label for="link">Link : </label><input name="link" id="link" size=25 type=text required /></br></br>
                <label for="button">Button : </label><input name="button" id="button" size=21 type=text required /></br></br>
                <input name="AddJoli" type="hidden" value="" />
                <div class= center>
                  <select name="filter">
                    <option value="1">Sérieux</option>
                    <option value="2">Passion</option>
                    <option value="3">Style</option>
                    <option value="4">Cocasse</option>
                  </select>
                  <input type=submit value="Add" />
                </div>
              </fieldset>
            </form>';
    } 
    function UpdateJoliForm($joli_title, $joli_subtitle, $joli_article, $joli_link, $joli_button, $input, $website_joliplume_header, $joli_filter, $page) {
      echo '<form method=post action="'.$page.'.php" id="AddJoliForm">
                <input class="admin" name="title" id="title" size=25 type=text value="'.$joli_title[$input].'" required /></br></br>
                <input class="admin" name="subtitle" id="subtitle" size=20 type=text value="'.$joli_subtitle[$input].'" required /></br></br>
                <input class="admin" name="article" id="article" size=22 type=text value="'.$joli_article[$input].'" required /></br></br>
                <input class="admin" name="link" id="link" size=25 type=text value="'.$joli_link[$input].'" required /></br></br>
                <input class="admin" name="button" id="button" size=21 type=text value="'.$joli_button[$input].'" required /></br></br>
                <input name="id" type="hidden" value="'.$input.'" /></br></br>
                <input name="UpdateJoli" type="hidden" value="" /></br></br>
                <div class= center>
                  <select name="filter">
                    <option value="1"';
                    if($joli_filter[$input] == 1) { echo 'selected';} 
                    echo '>'.$website_joliplume_header[1].'</option>
                    <option value="2"';
                    if($joli_filter[$input] == 2) { echo 'selected';} 
                    echo '>'.$website_joliplume_header[2].'</option>
                    <option value="3"';
                    if($joli_filter[$input] == 3) { echo 'selected';} 
                    echo '>'.$website_joliplume_header[3].'</option>
                    <option value="4"';
                    if($joli_filter[$input] == 4) { echo 'selected';} 
                    echo '>'.$website_joliplume_header[4].'</option>
                  </select>
                  <input type=submit value="Save" />
                </div>
            </form>
            <form method=post action="'.$page.'.php">
              <input name="id" type="hidden" value="'.$input.'" />
              <input type=hidden name="delete" />
              <input type=submit value="Delete" />
            </form>';
            if(isset($_POST['delete'])) { echo 'DELETE !!!!'; }
    }
    */
  }
?>