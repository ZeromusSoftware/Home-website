<?php
  include('include/head.php');
  echo '<body>';
  include('include/header.php');
  if($page == 'index') {
    include('include/section.php');
  }
  else {
    $webArray = array('site', 'stats', 'work');
    if(in_array($page, $webArray)) {
        include('include/system.php');
    }
    else {
        include('include/section_'.$page.'.php');
    }
  }
  include('include/footer.php');
  echo '</body>';
  include('include/foot.php');
?>
