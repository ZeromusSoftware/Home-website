<?php
  echo '<!doctype html>
        <html lang="'.$website_lang['content'].'">
          <head>
            <meta charset="utf-8" />';
  if($website_viewport['comment']) {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
  }
  if($website_description['comment']) {
    echo '<meta name="description" content="'.$website_description['content'].'" />';
  }
  if($website_style_admin['comment']) {
    for($style_number = 0; $style_number < $website_style_admin['number']; $style_number++) {
      echo '<link rel="stylesheet" href="css/'.$website_style_admin['content'].'/'.$website_style[$style_number].'" />';
    }
  }
  if($website_favicon['comment']) {
    echo  '<link rel="shortcut icon" type="image/png" href="ironpi.host56.com/'.$website_favicon['content'].'" />';
  }
  echo '<title>'.$page_title.' - '.$website_title['content'].'</title>
      </head>';
?>
