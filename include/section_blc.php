<?php
  if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'editor') {
    echo  '<section>';
    ArticleForm($website_blc_article0, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_blc_article1, $page);
    echo '</br>';
    ArticleForm($website_blc_article2, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_blc_article3, $page);
    echo '</br>';
    ArticleForm($website_blc_article4, $page);
    echo '</section>';
    separator();
    echo '<section>';
    ArticleForm($website_blc_article5, $page);
    echo '</section>';
  }
  else {
    echo '<div>
    ';
    echo '<div class="wrapper">
          <ul id="filter-container" class="grid cs-style-1">
        <li class="home">
      <figure>
     <section>
            <article>
              <h3>'.$website_blc_article1['content'].'</h3>
              <p>'.$website_blc_article2['content'].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <h3>'.$website_blc_article3['content'].'</h3>
              <p>'.$website_blc_article4['content'].'</p>
            </article>
        </section></figure>';
               //separator();
  echo '</li>';
  echo '<li class="home">
          <figure><section>'.
            //<h2>'.$website_section_subtitle[$section].'</h2>
            '<article>
                <h2 class="center">'.$website_blc_article5['content'].'</h2>
            </article>
            </section></figure>
</li>';
    echo '</ul></div></div>';
  }
?>
