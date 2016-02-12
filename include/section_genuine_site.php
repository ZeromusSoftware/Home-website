<?php
  if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'editor') {
    echo  '<section>';
    ArticleForm($website_genuine_site_section0_article0, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_genuine_site_section1_article0, $page);
    echo '</br>';
    ArticleForm($website_genuine_site_section1_article1, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_genuine_site_section2_article0, $page);
    echo '</br>';
    ArticleForm($website_genuine_site_section2_article1, $page);
    echo '</section>';
    separator();
    echo '<section>';
    ArticleForm($website_genuine_site_section0_article1, $page);
    echo '</section>';
  }
  else {
    echo '<div>
    <h1>'.$website_section_articleO[0].'</h1>
    ';
    separator();
    echo '<div class="wrapper">
          <ul id="filter-container" class="grid cs-style-1">
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_section_articleO[1].'</h1>
              <p>'.$website_section_article1[1].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <h1>'.$website_section_articleO[2].'</h1>
              <p>'.$website_section_article1[2].'</p>
            </article>
        </section></figure>';
               separator();
  echo '</li>';
  echo '<li class="home">
          <figure><section>'.
            //<h2>'.$website_section_subtitle[$section].'</h2>
            '<article>
                <h2 class="center">'.$website_section_article1[0].'</h2>
            </article>
            </section></figure>
</li>';
    echo '</ul></div></div>';
  }
?>
