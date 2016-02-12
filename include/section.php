<?php
  if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'editor') {
    TextForm($website_welcome, $page);
    echo  '<section>';
    ArticleForm($website_section1_article0, $page);
    echo '</br>';
    ArticleForm($website_section1_article1, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_section2_article0, $page);
    echo '</br>';
    ArticleForm($website_section2_article1, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_iron_system0, $page);
    echo '</br>';
    ArticleForm($website_iron_system1, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_hawk_project0, $page);
    echo '</br>';
    ArticleForm($website_hawk_project1, $page);
    echo '</section>';
  }
  else {
    echo '<div>'.
    //<h2>'.$website_welcome['content'].'</h2>
	   	      '<div class="wrapper">
          <ul id="filter-container" class="grid cs-style-1">';
  echo '<li class="home">
      <figure>
     <section>'.
            //<h1>'.$website_nav_section[0].'</h1>'.
            //<h2>'.$website_section_subtitle[$section].'</h2>
            '<article>
              <h2>'.$website_section_articleO[0].'</h2>
              <p>'.$website_section_article1[0].'</p>
            </article>
          </section></figure>
          <figure><section>'.
            //<h1>'.$website_nav_section[0].'</h1>'.
            //<h2>'.$website_section_subtitle[$section].'</h2>
            '<article><p>
              '.$website_section_articleO[1].$website_section_article1[1].'
            </p></article>
            </section></figure>';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_iron_system0['content'].'</h1>
              <p>'.$website_iron_system1['content'].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <h1>'.$website_hawk_project0['content'].'</h1>
              <p>'.$website_hawk_project1['content'].'</p>
            </article>
        </section></figure>
        </li>';
    echo '</ul></div></div>';
    }
?>
