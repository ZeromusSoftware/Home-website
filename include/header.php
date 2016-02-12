<?php
  echo '<div id="nav-background"><header>';
  if(isset($_SESSION['user']) AND $_SESSION['user']) {
    echo '<a href="index.php">
            <img id="logoBP" src="img/iron-hawk-simple.png" alt="logo" width=30 /></a>';
    /*if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'editor') {
        TextForm($website_title, $page);
      //SelectForm($website_style_admin, $page);
    }
    else {*/
        echo '<h1>'.$website_title['content'].'</h1>';
        if($page != 'index') {
            $iron_page = array('steel_dragon', 'plumeos', 'blc', 'falcon_eye', 'race_follower', 'project');
            $hawk_page = array('site', 'stats', 'work');
            if(in_array($page, $iron_page)) {
                echo '<h1> | </h1>
                       <a href=iron_system.php>
                         <h1>Iron System</h1>
                       </a>';
            }
            if(in_array($page, $hawk_page)) {
                echo '<h1> | </h1>
                       <a href=hawk_project.php>
                         <h1>Hawk Project</h1>
                       </a>';
            }
            echo '<h1> | </h1>
                   <h1>'.$page_title.'</h1>';
        }
    /*}*/
    if($BPlang) {
        echo '<a href="web.php?code=0"><img src="img/home.png" alt="lang" height=30 width=30 /></a>';
      }
      else {
        echo '<img src="img/menu.png" alt="lang" height=30 width=30 />';
      }
    //echo '<a href="lang.php"><img src="img/menu.png" alt="lang" height=30 width=30 /></a>';
    //echo '<a href="root.php?action=logout&page='.$page.'"><img src="img/logout.png" alt="logout" height=30 width=30 /></a>';
    if(isset($_SESSION['admin']) AND $_SESSION['admin']) {
        if($_SESSION['admin'] != 'editor') {
            echo '<a href=root.php?action=edit&page='.$page.'>
                        <img src="img/edit.png" alt="edit" height=30 width=30 />
                  </a>';
        }
        else {
            echo '<a href=root.php?action=save&page='.$page.'>
                    <img src="img/save.png" alt="edit" height=30 width=30 />
                  </a>';
        }
    }
  }
  else {
   echo '<a href="index.php">
            <img id="logoBP" src="img/iron-hawk-simple.png" alt="logo" width=30 /></a>';
    if($website_title['comment']) {
      echo '<div id="title">
                <h1>'.$website_title['content'].'</h1>';
      if($page != 'index') {
        $iron_page = array('steel_dragon', 'plumeos', 'blc', 'falcon_eye', 'project');
        $hawk_page = array('site', 'stats', 'work');
        if(in_array($page, $iron_page)) {
            echo '<h1> | </h1>
                   <a href=iron_system.php>
                     <h1>Iron System</h1>
                   </a>';
        }
        if(in_array($page, $hawk_page)) {
            echo '<h1> | </h1>
                   <a href=hawk_project.php>
                     <h1>Hawk Project</h1>
                   </a>';
        }
        echo '<h1> | </h1>
               <h1>'.$page_title.'</h1>';
      }
      echo '</div>';
      if($BPlang) {
        echo '<a href="web.php?code=0"><img src="img/home.png" alt="lang" height=30 width=30 /></a>';
      }
      else {
        echo '<img src="img/menu.png" alt="lang" height=30 width=30 />';
      }
    }
    Login($website_description, $_SESSION['lang'], $page);
  }
  echo '</header></div>';
?>