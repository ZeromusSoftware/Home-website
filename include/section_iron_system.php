<?php
  if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'editor') {
    TextForm($website_iron_system_title, $page);
    echo  '<section>';
    ArticleForm($website_iron_system_article, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_section3_article0, $page);
    echo '</br>';
    ArticleForm($website_section3_article1, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_section4_article0, $page);
    /*echo '</br>';
    ArticleForm($website_section4_article1, $page);*/
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_section5_article0, $page);
    /*echo '</br>';
    ArticleForm($website_section5_article1, $page);*/
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_section6_article0, $page);
    echo '</br>';
    ArticleForm($website_section6_article1, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_blc0, $page);
    echo '</br>';
    ArticleForm($website_blc1, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_blc2, $page);
    /*echo '</br>';
    ArticleForm($website_blc3, $page);*/
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_section8_article0, $page);
    /*echo '</br>';
    ArticleForm($website_section8_article1, $page);*/
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_section7_article0, $page);
    echo '</br>';
    ArticleForm($website_section7_article1, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_section10_article0, $page);
    echo '</br>';
    ArticleForm($website_section10_article1, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_section9_article0, $page);
    /*echo '</br>';
    ArticleForm($website_section9_article1, $page);*/
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_section12_article0, $page);
    /*echo '</br>';
    ArticleForm($website_section12_article1, $page);*/
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_section11_article0, $page);
    echo '</br>';
    ArticleForm($website_section11_article1, $page);
    echo '</section>';
    /*echo  '<section>';
    TextForm($website_nav_section3);
    echo '</br>';
    TextForm($website_section_subtitle3);
    echo '</br><div>
	         <div class="wrapper">';
      echo '<form method=post action="index.php">
              <input type=text class="admin" name="JoliHeader1" value="'.$website_joliplume1['content'].'" />
              <input type=text class="admin" name="JoliHeader2" value="'.$website_joliplume2['content'].'" />
              <input type=text class="admin" name="JoliHeader3" value="'.$website_joliplume3['content'].'" />
              <input type=text class="admin" name="JoliHeader4" value="'.$website_joliplume4['content'].'" />
              <input type=text class="admin" name="JoliHeader5" value="'.$website_joliplume5['content'].'" />
              <input name="JoliHeader" type="hidden" value="JoliHeader" />
              <input class="admin" type=submit value="" />
            </form>';
	  echo '<ul id="filter-container" class="grid cs-style-1"><li>';
	  AddJoliForm();
    echo '</li>';
    for($link = 0; $link < $max; $link++) {
      echo '<li>';
      UpdateJoliForm($joli_title, $joli_subtitle, $joli_article, $joli_link, $joli_button, $link, $website_joliplume_header, $joli_filter);
      echo '</li>';
    }
    echo '</li></ul></div></div></br></br>';
    echo '</section>';*/
  }
  else {
    echo '<div>
    <h1>'.$website_iron_system_title['content'].'</h1>
	   	      <div class="wrapper">
          <ul id="filter-container" class="grid cs-style-1">';
  echo '<li class="home">
        <p>'.
        $website_iron_system_article['content']
        .'</p>
  ';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_section_articleO[2].'</h1>
              <p>'.$website_section_article1[2].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <p>'.$website_section_articleO[3].'</p>
              <p>'.$website_section_article1[3].'</p>
            </article>
        </section></figure>';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <p>'.$website_section_articleO[4].'</p>
              <p>'.$website_section_article1[4].'</p>
            </article>
      </section></figure>
       <figure><section id="nav3">
            <article>
              <h1>'.$website_section_articleO[5].'</h1>
              <p>'.$website_section_article1[5].'</p>
            </article>
        </section></figure>';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_blc0['content'].'</h1>
              <p>'.$website_blc1['content'].'</p>
            </article>
      </section></figure>
       <figure><section id="nav3">
            <article>
              <p>'.$website_blc2['content'].'</p>
              <p>'.$website_blc3['content'].'</p>
            </article>
        </section></figure>';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <p>'.$website_section_articleO[7].'</p>
              <p>'.$website_section_article1[7].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <h1>'.$website_section_articleO[6].'</h1>
              <p>'.$website_section_article1[6].'</p>
            </article>
        </section></figure>';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_section_articleO[9].'</h1>
              <p>'.$website_section_article1[9].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <p>'.$website_section_articleO[8].'</p>
              <p>'.$website_section_article1[8].'</p>
            </article>
        </section></figure>';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <p>'.$website_section_articleO[11].'</p>
              <p>'.$website_section_article1[11].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <h1>'.$website_section_articleO[10].'</h1>
              <p>'.$website_section_article1[10].'</p>
            </article>
        </section></figure>
            </li>';
    echo '</ul></div></div>';
    }
?>
