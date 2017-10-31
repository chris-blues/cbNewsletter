<?php

  $debugout .= "<pre><b>[ bootstrap ]</b>\n";

  if (isset($_GET["view"])) {
    $cbNewsletter["config"]["view"] = $_GET["view"];
  }


  // Other classes
  $debugout .= str_pad("including /lib/classes/Email.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Email.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";






  // common bootstrap
  $debugout .= str_pad("including /lib/classes/bootstrap.common.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.common.php"))) ? : $debugout .= "FAILED\n";








  $debugout .= "</pre>\n";

?>
