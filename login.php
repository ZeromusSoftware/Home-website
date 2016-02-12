<?php
  session_start();
  $page = 'login';
  $page_title = 'Connexion';
  require('require/model.php');
  require('require/controller.php');
  include('include/head.php');
  echo '<body>';
  include('include/header.php');
  echo '<section class="loginBody">';
  if(isset($_GET['password']) AND $_GET['password'] == 'forgot') {
    echo '<h1>Mot de passe oublié</h1>';
  }
  else {
      echo '<h1>Nous vous attendions';
      if(isset($_GET['register']) AND $_GET['register'] == 'finished' AND isset($_SESSION['login'])) {
      echo ' '.htmlspecialchars($_SESSION['login']);
      }
      echo '</h1>';
  }
  echo '<article class="loginBox">';
  if(isset($_POST['error'])) {
    echo '<img src="img/warning.png" alt="" />'; 
  }
  else {
    if(isset($_GET['password']) AND $_GET['password'] == 'forgot') {
        echo '<img src="img/email.png" alt="" />';
    }
    else {
        echo '<img src="img/key.png" alt="" />';
    }
  }
  echo '<form method="POST" action="login.php';
  if(isset($_GET['from'])) {
    echo '?from='.$_GET['from'];
  }
  echo '">
        <p>';
  if(isset($_GET['password']) AND $_GET['password'] == 'forgot') {
        echo '</br></br>
             <input type=text class="textInput" name="email" placeholder="Entrez votre email" required />
             </br>
             <input type=submit class="blueButton" name="resendMail" value="Envoyez vous vos identifiants" />
             </br></br>';
  }
  else {
      echo '<input type=text name="login" class="textInput" ';
      if(isset($_GET['register']) AND $_GET['register'] == 'finished' AND isset($_SESSION['login'])) {
        echo 'value="'.htmlspecialchars($_SESSION['login']).'" ';
      }
      echo 'placeholder="Identifiant" required';
      if(isset($_GET['project']) AND $_GET['project'] == 'true') {
        echo ' autofocus';
      }
      echo ' /></br>
             <input type=password name="password" class="textInput" placeholder="Mot de passe" required';
      if(isset($_GET['register']) AND $_GET['register'] == 'finished' AND isset($_SESSION['login'])) {
        echo ' autofocus ';
      }
      echo '/></br>
            <input type=text name="comment" class="textInput" placeholder="Un commentaire ?" /></br>';
      echo '<input type=hidden name="connect" value="" />
            <input type=hidden name="error" />
            <input type=hidden name="signin" />
            <input type=submit class="blueButton" name="signin" value="Connectez-vous" /></br>';
  }
  echo '</p>
        </form>';
  if(!isset($_GET['password'])) {
      if(isset($_POST['error'])) {
        echo '</br><p>Problème de connexion</p>'; 
      }
      echo  '</article>
      <h3><a href="register.php?lang='.$_SESSION['lang'].'">Pas encore inscrit ?</a> <a href="'.$page.'.php?lang='.$_SESSION['lang'].'&password=forgot">Mot de passe oublié ?</a></h3>
      </section>';
  }
  include('include/footer.php');
  echo '</body>';
  include('include/foot.php');
?>
