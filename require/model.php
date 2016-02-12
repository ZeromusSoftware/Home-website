<?php
/*--------Langage system--------*/
  if(isset($page)) {
    $lang = array("fr", "en"); //langage list
    if(isset($_GET['lang']) AND in_array($_GET['lang'],$lang)) {
        $_SESSION['lang'] = $_GET['lang'];
        header('Location: '.$page.'.php');
    }
    else {
        if(!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = 'fr';
        }
    }
/*---------Dowgs----------------*/
    if(isset($_GET['title'])) {
        if($_GET['title'] == 'dowgs') {
            $_SESSION['dowgs'] = true;
        }
        if($_GET['title'] == 'exit') {
            session_destroy();
            session_start();
            header('Location: index.php');
        }
    }
    else {
        if(isset($_SESSION['dowgs']) AND $_SESSION['dowgs']) {
            header('Location: '.$page.'.php?title=dowgs');
        }
    }
  }
/*-------Website Info-----------*/
  require('require/info.php');
/*-------Stop system------------*/
  if($stop) {
    header('Location: stop.html');
  }
/*-------Data base name---------*/
  if(isset($localhost)) {
    if(!$localhost) {
      $dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
      try {
         $pdo = new PDO($dsn, $admin_login, $admin_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
      }
      catch(PDOException $exception) {
        exit('DBtest log in failure. </br>
               Localhost mode: disable');
      }
    }
    else {
      $dsn = 'mysql:host='.$host_local.';dbname='.$dbname_local.';charset=utf8';
      try {
          $pdo = new PDO($dsn, $admin_login_local, $admin_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
      }
      catch(PDOException $exception) {
          exit('DBtest log in failure. </br>
                 Localhost mode: enable');
      }
    }
  }
  else {
    exit('Localhost problem');
  }
/*-------------------------------
    $website = array();
    if($_SESSION['lang'] == 'fr') {
        $root = $pdo->prepare('SELECT * FROM root WHERE page = :page OR page = all ORDER BY id');
    }
    if($_SESSION['lang'] == 'en') {
        $root = $pdo->prepare('SELECT * FROM rooten WHERE page = :page OR page = all ORDER BY id');
    }
    $root ->execute(array('page' => $page))
    //IN root.SQL : 'comment' -> 'page'
    while($display = $root->fetch()) {
        if($display['name'] == 'section') {
            $website[$display['name'].$display['number']] = $display['content']
        }
        else {
            $website[$display['name']] = $display['content'];
        }
    }
    $root->closeCursor();
-------------------------------*/
  if(isset($_SESSION['dowgs']) AND $_SESSION['dowgs'] == true) {
    $website = $pdo->prepare('SELECT * FROM dowgs WHERE name = ?');
  }
  else {
      if($_SESSION['lang'] == 'fr') {
        $website = $pdo->prepare('SELECT * FROM root WHERE name = ?');
      }
      if($_SESSION['lang'] == 'en') {
        $website = $pdo->prepare('SELECT * FROM rooten WHERE name = ?');
      }
  }
  $website->execute(array('website_title'));
  $website_title = $website->fetch();
  $website->execute(array('website_subtitle'));
  $website_subtitle = $website->fetch();
  $website->execute(array('website_description'));
  $website_description = $website->fetch();
  $website->execute(array('website_style_admin'));
  $website_style_admin = $website->fetch();
  $website->execute(array('website_style1'));
  $website_style1 = $website->fetch();
  $website->execute(array('website_style2'));
  $website_style2 = $website->fetch();
  $website->execute(array('website_style3'));
  $website_style3 = $website->fetch();
  $website->execute(array('website_style4'));
  $website_style4 = $website->fetch();
  $website->execute(array('website_style5'));
  $website_style5 = $website->fetch();
  $website->execute(array('website_style6'));
  $website_style6 = $website->fetch();
  $website->execute(array('website_style7'));
  $website_style7 = $website->fetch();
  $website->execute(array('website_favicon'));
  $website_favicon = $website->fetch();
  $website->execute(array('website_lang'));
  $website_lang = $website->fetch();
  $website->execute(array('website_viewport'));
  $website_viewport = $website->fetch();
  $website->execute(array('website_nav_page'));
  $website_nav_page = $website->fetch();
  $website->execute(array('website_nav_section1'));
  $website_nav_section1 = $website->fetch();
  if($page == 'index') {
    $website->execute(array('website_welcome'));
    $website_welcome = $website->fetch();
    $website->execute(array('website_iron_system0'));
    $website_iron_system0 = $website->fetch();
    $website->execute(array('website_iron_system1'));
    $website_iron_system1 = $website->fetch();
    $website->execute(array('website_hawk_project0'));
    $website_hawk_project0 = $website->fetch();
    $website->execute(array('website_hawk_project1'));
    $website_hawk_project1 = $website->fetch();
    $website->execute(array('website_section_subtitle1'));
    $website_section_subtitle1 = $website->fetch();
    $website->execute(array('website_section1_article0'));
    $website_section1_article0 = $website->fetch();
    $website->execute(array('website_section1_article1'));
    $website_section1_article1 = $website->fetch();
    $website->execute(array('website_nav_section2'));
    $website_nav_section2 = $website->fetch();
    $website->execute(array('website_section2_article0'));
    $website_section2_article0 = $website->fetch();
    $website->execute(array('website_section2_article1'));
    $website_section2_article1 = $website->fetch();
  }
  if($page == 'iron_system') {
    $website->execute(array('website_blc0'));
    $website_blc0 = $website->fetch();
    $website->execute(array('website_blc1'));
    $website_blc1 = $website->fetch();
    $website->execute(array('website_blc2'));
    $website_blc2 = $website->fetch();
    $website->execute(array('website_blc3'));
    $website_blc3 = $website->fetch();
    $website->execute(array('website_iron_system_title'));
    $website_iron_system_title = $website->fetch();
    $website->execute(array('website_iron_system_article'));
    $website_iron_system_article = $website->fetch();
    $website->execute(array('website_section_subtitle2'));
    $website_section_subtitle2 = $website->fetch();
    $website->execute(array('website_nav_section3'));
    $website_nav_section3 = $website->fetch();
    $website->execute(array('website_section_subtitle3'));
    $website_section_subtitle3 = $website->fetch();
    $website->execute(array('website_section3_article0'));
    $website_section3_article0 = $website->fetch();
    $website->execute(array('website_section3_article1'));
    $website_section3_article1 = $website->fetch();
    $website->execute(array('website_section4_article0'));
    $website_section4_article0 = $website->fetch();
    $website->execute(array('website_section4_article1'));
    $website_section4_article1 = $website->fetch();
    $website->execute(array('website_section5_article0'));
    $website_section5_article0 = $website->fetch();
    $website->execute(array('website_section5_article1'));
    $website_section5_article1 = $website->fetch();
    $website->execute(array('website_section6_article0'));
    $website_section6_article0 = $website->fetch();
    $website->execute(array('website_section6_article1'));
    $website_section6_article1 = $website->fetch();
    $website->execute(array('website_section7_article0'));
    $website_section7_article0 = $website->fetch();
    $website->execute(array('website_section7_article1'));
    $website_section7_article1 = $website->fetch();
    $website->execute(array('website_section8_article0'));
    $website_section8_article0 = $website->fetch();
    $website->execute(array('website_section8_article1'));
    $website_section8_article1 = $website->fetch();
    $website->execute(array('website_section9_article0'));
    $website_section9_article0 = $website->fetch();
    $website->execute(array('website_section9_article1'));
    $website_section9_article1 = $website->fetch();
    $website->execute(array('website_section10_article0'));
    $website_section10_article0 = $website->fetch();
    $website->execute(array('website_section10_article1'));
    $website_section10_article1 = $website->fetch();
    $website->execute(array('website_section11_article0'));
    $website_section11_article0 = $website->fetch();
    $website->execute(array('website_section11_article1'));
    $website_section11_article1 = $website->fetch();
    $website->execute(array('website_section12_article0'));
    $website_section12_article0 = $website->fetch();
    $website->execute(array('website_section12_article1'));
    $website_section12_article1 = $website->fetch();
  }
  if($page == 'hawk_project') {
    $website->execute(array('website_hawk_project_title'));
    $website_hawk_project_title = $website->fetch();
    $website->execute(array('website_hawk_project_article'));
    $website_hawk_project_article = $website->fetch();
    $website->execute(array('website_hawk_project_article0'));
    $website_hawk_project_article0 = $website->fetch();
    $website->execute(array('website_hawk_project_article1'));
    $website_hawk_project_article1 = $website->fetch();
    $website->execute(array('website_hawk_project2'));
    $website_hawk_project2 = $website->fetch();
    $website->execute(array('website_hawk_project3'));
    $website_hawk_project3 = $website->fetch();
    $website->execute(array('website_hawk_project4'));
    $website_hawk_project4 = $website->fetch();
    $website->execute(array('website_hawk_project5'));
    $website_hawk_project5 = $website->fetch();
    $website->execute(array('website_hawk_project6'));
    $website_hawk_project6 = $website->fetch();
    $website->execute(array('website_hawk_project7'));
    $website_hawk_project7 = $website->fetch();
    $website->execute(array('website_hawk_project8'));
    $website_hawk_project8 = $website->fetch();
    $website->execute(array('website_hawk_project9'));
    $website_hawk_project9 = $website->fetch();
  }
  if($page == 'steel_dragon') {
    $website->execute(array('website_steel_dragon_section0_article0'));
    $website_steel_dragon_section0_article0 = $website->fetch();
    $website->execute(array('website_steel_dragon_section0_article1'));
    $website_steel_dragon_section0_article1 = $website->fetch();
    $website->execute(array('website_steel_dragon_section1_article0'));
    $website_steel_dragon_section1_article0 = $website->fetch();
    $website->execute(array('website_steel_dragon_section1_article1'));
    $website_steel_dragon_section1_article1 = $website->fetch();
    $website->execute(array('website_steel_dragon_section2_article0'));
    $website_steel_dragon_section2_article0 = $website->fetch();
    $website->execute(array('website_steel_dragon_section2_article1'));
    $website_steel_dragon_section2_article1 = $website->fetch();
  }
  if($page == 'plumeos') {
    $website->execute(array('website_plumeos_section0_article0'));
    $website_plumeos_section0_article0 = $website->fetch();
    $website->execute(array('website_plumeos_section0_article1'));
    $website_plumeos_section0_article1 = $website->fetch();
    $website->execute(array('website_plumeos_section1_article0'));
    $website_plumeos_section1_article0 = $website->fetch();
    $website->execute(array('website_plumeos_section1_article1'));
    $website_plumeos_section1_article1 = $website->fetch();
    $website->execute(array('website_plumeos_section2_article0'));
    $website_plumeos_section2_article0 = $website->fetch();
    $website->execute(array('website_plumeos_section2_article1'));
    $website_plumeos_section2_article1 = $website->fetch();
  }
  if($page == 'blc') {
    $website->execute(array('website_blc_article0'));
    $website_blc_article0 = $website->fetch();
    $website->execute(array('website_blc_article1'));
    $website_blc_article1 = $website->fetch();
    $website->execute(array('website_blc_article2'));
    $website_blc_article2 = $website->fetch();
    $website->execute(array('website_blc_article3'));
    $website_blc_article3 = $website->fetch();
    $website->execute(array('website_blc_article4'));
    $website_blc_article4 = $website->fetch();
    $website->execute(array('website_blc_article5'));
    $website_blc_article5 = $website->fetch();
  }
  if($page == 'falcon_eye') {
    $website->execute(array('website_falcon_eye_section0_article0'));
    $website_falcon_eye_section0_article0 = $website->fetch();
    $website->execute(array('website_falcon_eye_section0_article1'));
    $website_falcon_eye_section0_article1 = $website->fetch();
    $website->execute(array('website_falcon_eye_section1_article0'));
    $website_falcon_eye_section1_article0 = $website->fetch();
    $website->execute(array('website_falcon_eye_section1_article1'));
    $website_falcon_eye_section1_article1 = $website->fetch();
    $website->execute(array('website_falcon_eye_section2_article0'));
    $website_falcon_eye_section2_article0 = $website->fetch();
    $website->execute(array('website_falcon_eye_section2_article1'));
    $website_falcon_eye_section2_article1 = $website->fetch();
  }
  if($page == 'race_follower') {
    $website->execute(array('website_race_follower_article0'));
    $website_race_follower_article0 = $website->fetch();
    $website->execute(array('website_race_follower_article1'));
    $website_race_follower_article1 = $website->fetch();
    $website->execute(array('website_race_follower_article2'));
    $website_race_follower_article2 = $website->fetch();
    $website->execute(array('website_race_follower_article3'));
    $website_race_follower_article3 = $website->fetch();
    $website->execute(array('website_race_follower_article4'));
    $website_race_follower_article4 = $website->fetch();
    $website->execute(array('website_race_follower_article5'));
    $website_race_follower_article5 = $website->fetch();
  }
  if($page == 'genuine_site') {
    $website->execute(array('website_genuine_site_section0_article0'));
    $website_genuine_site_section0_article0 = $website->fetch();
    $website->execute(array('website_genuine_site_section0_article1'));
    $website_genuine_site_section0_article1 = $website->fetch();
    $website->execute(array('website_genuine_site_section1_article0'));
    $website_genuine_site_section1_article0 = $website->fetch();
    $website->execute(array('website_genuine_site_section1_article1'));
    $website_genuine_site_section1_article1 = $website->fetch();
    $website->execute(array('website_genuine_site_section2_article0'));
    $website_genuine_site_section2_article0 = $website->fetch();
    $website->execute(array('website_genuine_site_section2_article1'));
    $website_genuine_site_section2_article1 = $website->fetch();
  }
  if($page == 'members') {
    $website->execute(array('website_welcome_members0'));
    $website_welcome_members0 = $website->fetch();
    $website->execute(array('website_welcome_members1'));
    $website_welcome_members1 = $website->fetch();
  }
  $website->execute(array('website_section'));
  $website_section = $website->fetch();
  $website->execute(array('website_nav_section_number'));
  $website_nav_section_number = $website->fetch();
  $website->execute(array('website_footer'));
  $website_footer = $website->fetch();
  $website->execute(array('website_script'));
  $website_script = $website->fetch();
  /*$website->execute(array('website_joliplume'));
  $website_joliplume = $website->fetch();
  $website->execute(array('website_joliplume1'));
  $website_joliplume1 = $website->fetch();
  $website->execute(array('website_joliplume2'));
  $website_joliplume2 = $website->fetch();
  $website->execute(array('website_joliplume3'));
  $website_joliplume3 = $website->fetch();
  $website->execute(array('website_joliplume4'));
  $website_joliplume4 = $website->fetch();
  $website->execute(array('website_joliplume5'));
  $website_joliplume5 = $website->fetch();*/
  $website->closeCursor();
  if($page == 'register') {
    $accounts = $pdo->query('SELECT id FROM accounts ORDER BY id DESC');
    $accounts_number = $accounts->fetch();
    $accounts->closeCursor();
  }
  if($page == 'members') {
    $accounts_ironhawk = $pdo->query('SELECT * FROM accounts WHERE ironhawk = "true"');
    $i = 0;
    while($ironhawk = $accounts_ironhawk->fetch()) {
        $name[$i] = $ironhawk['name'];
        $fonction[$i] = $ironhawk['fonction'];
        $description[$i] = $ironhawk['description'];
        $login[$i] = $ironhawk['login'];
        $i++;
    }
    $accounts_number_ironhawk = $i;
    for($i = 0; $i < $accounts_number_ironhawk; $i++) {
        $paradoxe01['name'] = $name[0];
        $paradoxe01['fonction'] = $fonction[0];
        $paradoxe01['description'] = $description[0];
        $paradoxe01['login'] = $login[0];
        $wuwu['name'] = $name[1];
        $wuwu['fonction'] = $fonction[1];
        $wuwu['description'] = $description[1];
        $wuwu['login'] = $login[1];
        $pioupiou['name'] = $name[2];
        $pioupiou['fonction'] = $fonction[2];
        $pioupiou['description'] = $description[2];
        $pioupiou['login'] = $login[2];
        $joh['name'] = $name[3];
        $joh['fonction'] = $fonction[3];
        $joh['description'] = $description[3];
        $joh['login'] = $login[3];
    }
    $accounts_ironhawk->closeCursor();
  }
  /*$plume = $pdo->query('SELECT * FROM joliplume ORDER BY id DESC');
  $joli = $plume->fetch();
  $joli_title[0] = $joli['title'];
  $joli_subtitle[0] = $joli['subtitle'];
  $joli_article[0] = $joli['article'];
  $joli_link[0] = $joli['link'];
  $joli_button[0] = $joli['button'];
  $joli_filter[0] = $joli['filter'];
  $max = $joli['id'];
  for($number = 1; $number <= $max; $number++) {
    $joli = $plume->fetch();
    $joli_title[$number] = $joli['title'];
    $joli_subtitle[$number] = $joli['subtitle'];
    $joli_article[$number] = $joli['article'];
    $joli_link[$number] = $joli['link'];
    $joli_button[$number] = $joli['button'];
    $joli_filter[$number] = $joli['filter'];
  }
  $plume->closeCursor();*/
?>