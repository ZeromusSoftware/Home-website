<?php
if(isset($_SESSION['admin']) AND $_SESSION['admin']) {
    echo  '<section>';
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
	  echo '<ul id="filter-container" class="grid cs-style-1"><li id="addIdea">';
	  AddIdeaForm($website_joliplume_header);
    echo '</li>';
    for($link = 0; $link < $max; $link++) {
      echo '<li>';
      UpdateJoliForm($joli_title, $joli_subtitle, $joli_article, $joli_link, $joli_button, $link, $website_joliplume_header, $joli_filter);
      echo '</li>';
    }
    echo '</li></ul></div></div></br></br>';
    echo '</section>';
  }
  else {
    echo '<section><div class="wrapper">
            <ul id="filter-container" class="grid cs-style-1"><li id="addIdea">';
    AddIdeaForm($website_joliplume_header);
    echo '</li>';
    for($link = 0; $link < $max; $link++) {
      echo '<li class="'.$joli_filter[$link].'">
	            <figure><text><filter'.$joli_filter[$link].'>'.htmlspecialchars($joli_title[$link]).'</'.$joli_filter[$link].'></text>
              <figcaption>
               <h1>'.htmlspecialchars($joli_title[$link]).'</h1>
               <h3>'.htmlspecialchars($joli_subtitle[$link]).'</h3>
                <p>'.htmlspecialchars($joli_article[$link]).'</p>
                <a href="'.$joli_link[$link].'" target="about_blanc">'.$joli_button[$link].'</a>
              </figcaption>
              </figure>
            </li>';
    }
    echo '</ul></div></div></section>';
  }
?>