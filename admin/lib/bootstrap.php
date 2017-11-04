<?php

  $Debugout->add("<pre><b>[ bootstrap ]</b>");




  include_once(checkout("/lib/bootstrap.common.php"));


  include_once(checkout("/admin/lib/classes/Template.class.php"));



  // HTML header
  include_once(checkout("/admin/views/header.php"));





  $Debugout->add("</pre>");


?>
