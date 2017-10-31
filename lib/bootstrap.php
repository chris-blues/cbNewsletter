<?php

  $Debugout->add("<pre><b>[ bootstrap ]</b>");

  if (isset($_GET["view"])) {
    $cbNewsletter["config"]["view"] = $_GET["view"];
  }


  // Other classes
  include_once(checkout("/lib/classes/Email.class.php"));






  // common bootstrap
  include_once(checkout("/lib/bootstrap.common.php"));








  $Debugout->add("</pre>");

?>
