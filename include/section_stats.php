<?php
/*----------------------------INIT VAR----------------------------------*/
        $stats_all = 0;
        $stats_one = 0;
        $stats_all_members = 0;
        $stats_active_members = 0;
        $stats_score = 0;
        $stats_site = 0;
        $stats_site_rows = 0;
        $stats_site_number = 0;
        $stats_page_number = 0;
        $stats_stats = 0;
        $stats_work = 0;
        $stats_work_number = 0;
        $stats_dowgs = 0;
        $stats_team = 0;
        $stats_contest = 0;
        $testArray = array();
        $siteArray = array();
        $occurence = array();
        $pageArray = array();
        $teamArray = array();
        $workArray = array();
        $contestArray = array();
        $j = 1;
        $k = 1;
        $l = 1;
        $m = 0;
/*----------------------------VISITES-----------------------------------*/
        $stats_query = $pdo->query('SELECT * FROM stats ORDER BY id DESC');
        while($account = $stats_query->fetch()) {
            $stats_all++;
            if($account['login'] != NULL) {
                $loginArray[$m] = $account['date'];
                $m++;
            }
            if(!in_array($account['ip'], $testArray)) {
                $stats_one++;
                $testArray[$j] = $account['ip'];
                $j++;
            }
            if($account['page'] != NULL) {
                if(!in_array($account['page'], $pageArray)) {
                    $stats_page_number++;
                    $pageArray[$l] = $account['page'];
                    $occurence[$pageArray[$l]] = 1;
                    $l++;
                }
                else {
                    $occurence[$account['page']]++;
                }
            }
        }
        $stats_query->closeCursor();
        $stats_last_login = $loginArray[0];
        $stats_pageArray = array_keys($occurence, max($occurence));
        $stats_page = $stats_pageArray[0];
/*----------------------------MEMBRES-----------------------------------*/
        $stats_query = $pdo->query('SELECT * FROM accounts ORDER BY id DESC');
        while($account = $stats_query->fetch()) {
            $memberArray[$stats_all_members] = $account['creation'];
            $stats_all_members++;
            if($account['actif'] == 1) {
                $stats_active_members++;
            }
            if($account['score'] == 'true') {
                $stats_score++;
            }
            if($account['site'] == 'true') {
                $stats_site++;
            }
            if($account['stats'] == 'true') {
                $stats_stats++;
            }
            if($account['work'] == 'true') {
                $stats_work++;
            }
            if($account['dowgs'] == 'true') {
                $stats_dowgs++;
            }
        }
        $stats_query->closeCursor();
        $stats_last_signin = $memberArray[0];
/*----------------------------SITE-----------------------------------*/
        $k = 0;
        $stats_query = $pdo->query('SELECT * FROM site');
        while($account = $stats_query->fetch()) {
            $stats_site_rows++;
            if(!in_array($account['keyword'], $siteArray)) {
                $stats_site_number++;
                $siteArray[$k] = $account['keyword'];
                $k++;
            }
        }
        $stats_query->closeCursor();
/*----------------------------SCORE-----------------------------------*/
        $i = 0;
        $j = 0;
        $stats_query = $pdo->query('SELECT * FROM score ORDER BY id DESC');
        while($account = $stats_query->fetch()) {
            if(!in_array($account['team'], $teamArray)) {
                $stats_team++;
                $teamArray[$i] = $account['team'];
                $i++;
            }
            if(!in_array($account['contest'], $contestArray)) {
                $stats_contest++;
                $contestArray[$j] = $account['contest'];
                $j++;
            }
        }
        $stats_query->closeCursor();
/*----------------------------WORK-----------------------------------*/
        $i = 0;
        $stats_query = $pdo->query('SELECT * FROM work ORDER BY id DESC');
        while($account = $stats_query->fetch()) {
            if(!in_array($account['keyword'], $workArray)) {
                $stats_work_number++;
                $workArray[$i] = $account['keyword'];
                $i++;
            }
        }
        $stats_query->closeCursor();
/*----------------------------STATS-----------------------------------*/
        $stats = array(
            'view' => $stats_all,
            'viewer' => $stats_one,
            'page' => $stats_page,
            'site' => $stats_site,
            'page_number' => $stats_page_number,
            'site_create' => $stats_site_number,
            'member' => $stats_all_members,
            'last_signin' => $stats_last_signin,
            'connected' => $stats_active_members,
            'last_login' => $stats_last_login,
            'work' => $stats_work,
            'project' => $stats_work_number,
            'score' => $stats_score,
            'team' => $stats_team,
            'contest' => $stats_contest,
            'stats' => $stats_stats,
        );
        $stats['count'] = count($stats)+6;
/*----------------------------SECTION-----------------------------------*/
    echo '<div>'.
    //<h1>Coding in progress...</h1>
         '<div class="wrapper">
          <ul id="filter-container" class="grid cs-style-1">';
               //separator();
  echo'<li class="home">
      <figure>
     <section>
            <article>
              <h3 class=center>Site</h3>
              <p>
                Nombre total de visites du site : '.$stats['view'].'</br>
                Nombre de visiteurs différents : '.$stats['viewer'].'</br>
                Nombre de pages du site : '.$stats['page_number'].'</br>
                Page la plus vue : <a href='.$stats['page'].'.php>'.$stats['page'].'.php</a></br>
                Utilisateurs de Site : '.$stats['site'].'</br>
                Sites web créés avec le service : '.$stats['site_create'].' </br>
                Avancement du projet : 30% </br>
              </p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <h3 class=center>Work</h3>
              <p>
                Membres inscrits : '.$stats['member'].'</br>
                Dernière inscription : '.$stats['last_signin'].'</br>
                Membres connectés : '.$stats['connected'].'</br>
                Dernière connexion : '.$stats['last_login'].'</br>
                Utilisateurs de Work : '.$stats['work'].'</br>
                Nombre de projets gérés : '.$stats['project'].' </br>
                Avancement du projet : 5% </br>
              </p>
            </article>
        </section></figure>';
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h3 class=center>Score</h3>
              <p>
                Utilisateurs de Score : '.$stats['score'].'</br>
                Nombre d\'équipes inscrites : '.$stats['team'].' </br>
                Nombre de compétitions : '.$stats['contest'].' </br>
                Avancement du projet : 80% </br>
              </p>
            </article>
      </section></figure>
       <figure><section id="nav3">
            <article>
              <h3 class=center>Stats</h3>
              <p>
                Nombre de variables calculées : '.$stats['count'].' </br>
                Utilisateurs de Stats : '.$stats['stats'].'</br>
                Avancement du projet : 40% </br>
              </p>
            </article>
        </section></figure>';
  echo'</li>';
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h3 class=center>Dowgs</h3>
              <p>
                Avancement du projet : 1% </br>
              </p>
            </article>
      </section></figure>';
  echo'</li>';
    echo '</ul></div></div>';
?>