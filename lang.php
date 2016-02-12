<?php
  session_start();
  $page = 'lang';
  $page_title = 'Langages';
  require('require/model.php');
  require('require/controller.php');
  echo '<!doctype html>
        <html'.$website_lang['content'].'>
          <head>
            <meta charset="utf-8" />';
  if($website_viewport['comment']) {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
  }
  if($website_description['comment']) {
    echo '<meta name="description" content="'.$website_description['content'].'" />';
  }
  if($website_style_admin['comment']) {
    echo '<link rel="stylesheet" href="css/home.css" />';
  }
  if($website_favicon['comment']) {
    echo  '<link rel="shortcut icon" type="image/png" href="'.$website_favicon['content'].'" />';
  }
  echo '<title>'.$website_title['content'].'</title>
      </head>';
  echo '<body>';
    function homeLink($lang, $country) {
        echo '<a href="index.php?lang='.$lang.'" data-filter=".home">'.$country.'</a>';
    }
    echo '<img src="img/bp-home.png" alt="Logo" />
           <p>';
    homeLink('fr', 'FRANCE');
    echo ' | ';
    //homeLink('ru', 'RUSSIA');
    //echo ' | ';
    //homeLink('cn', 'CHINA');
    //echo ' | ';
    homeLink('en', 'INTERNATIONAL');
    echo '</p></body>';
  include('include/foot.php');
?>
