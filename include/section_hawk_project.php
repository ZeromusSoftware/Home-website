<?php
  if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 'editor') {
    TextForm($website_hawk_project_title, $page);
    echo  '<section>';
    ArticleForm($website_hawk_project_article, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_hawk_project_article0, $page);
    echo '</br>';
    ArticleForm($website_hawk_project_article1, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_hawk_project2, $page);
    echo '</br>';
    ArticleForm($website_hawk_project3, $page);
    echo '</section>';
    separator();
    echo  '<section>';
    ArticleForm($website_hawk_project4, $page);
    echo '</br>';
    ArticleForm($website_hawk_project5, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_hawk_project6, $page);
    echo '</br>';
    ArticleForm($website_hawk_project7, $page);
    echo '</section>';
    echo  '<section>';
    ArticleForm($website_hawk_project8, $page);
    echo '</br>';
    ArticleForm($website_hawk_project9, $page);
    echo '</section>';
  }
  else {
    echo '<div>
    <h1>'.$website_hawk_project_title['content'].'</h1>
	   	      <div class="wrapper">
          <ul id="filter-container" class="grid cs-style-1">';
  echo '<li class="home">
        <p>'.
        $website_hawk_project_article['content']
        .'</p>
  ';
               separator();
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_hawk_project_article0['content'].'</h1>
              <p>'.$website_hawk_project_article1['content'].'</p>
            </article>
      </section></figure>
       <figure><section>
            <article>
              <h1>'.$website_hawk_project2['content'].'</h1>
              <p>'.$website_hawk_project3['content'].'</p>
            </article>
        </section></figure>';
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_hawk_project4['content'].'</h1>
              <p>'.$website_hawk_project5['content'].'</p>
            </article>
      </section></figure>
       <figure><section id="nav3">
            <article>
              <h1>'.$website_hawk_project6['content'].'</h1>
              <p>'.$website_hawk_project7['content'].'</p>
            </article>
        </section></figure>';
  echo'</li>
        <li class="home">
      <figure>
     <section>
            <article>
              <h1>'.$website_hawk_project8['content'].'</h1>
              <p class=center>'.$website_hawk_project9['content'].'</p>
            </article>
      </section></figure>';
  echo'</li>';
    echo '</ul></div></div>';
    }
?>