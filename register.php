<?php
  session_start();
  $page = 'register';
  $page_title = 'Inscription';
  require('require/model.php');
  require('require/controller.php');
  include('include/head.php');
  echo '<body>';
  include('include/header.php');
  echo '<section class="loginBody">
    <h1>Rejoignez nos '.$accounts_number['id'].' utilisateurs</h1>
    <article class="loginBox">';
  if(isset($_POST['error'])) {
    echo '<img src="img/warning.png" alt="" />'; 
  }
  else {
    if(isset($_POST['register']) AND $_POST['register'] == 'sentMail') {
        echo '<img src="img/email.png" alt="" />';
    }
    else {
        echo '<img src="img/community.png" alt="" />';
    }
  }
  echo '<form method="POST" action="register.php">
           <p>';
  if (isset($_POST['register']) AND $_POST['register'] == 'sentMail') {
      echo '</br></br>
             <input type=text class="textInput" name="email" value="'.$_SESSION['email'].'" />
             </br>
             <input type=submit class="blueButton" name="resendMail" value="Renvoyez un email" />
             </br></br>';
  }
  else {
      echo '<input type=text name="login" class="textInput" placeholder="Identifiant souhaité" required /></br>
             <input type=password name="password" class="textInput midWidth" placeholder="Mot de passe" required />
             <input type=password name="password2" class="textInput midWidth" placeholder="Vérification" required /></br>
             <input type=email name="email" class="textInput" placeholder="Email" required /></br>
             <input type=submit class="blueButton" name="register" value="Confirmez votre inscription" />';
  }
      echo '</p>
         </form>';
  if(isset($_POST['error'])) {
    echo '</br><p>Problème: '.$_POST['error'].'</p>'; 
  }
  echo '</article>
  <h3>';
    if(isset($_POST['register']) AND $_POST['register'] == 'sentMail') {
        echo 'Nous vous avons envoyé un email de confirmation.</br>
               Vérifiez vos spam.';
    }
    else {
        echo '<a href="login.php?lang='.$_SESSION['lang'].'">Déjà inscrit ?</a>';
    }
    echo '</h3>
  </section>';
  include('include/footer.php');
  echo '</body>';
  include('include/foot.php');
?>
