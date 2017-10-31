<?php

  $debugout .= "<pre><b>[ bootstrap ]</b>\n";



  $debugout .= str_pad("including /lib/bootstrap.common.php", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.common.php"))) ? : $debugout .= "FAILED\n";



  // HTML header
  $debugout .= str_pad("including /admin/views/header.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/header.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";




  $debugout .= "</pre>";


?>
