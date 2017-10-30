<?php

  $debugout .= "<pre><b>[ bootstrap ]</b>\n";

  if (isset($_GET["view"])) {
    $cbNewsletter["config"]["view"] = $_GET["view"];
  }

  // general functions
  $debugout .= str_pad("including /lib/functions.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/functions.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";

  // OOP based HTML output
  $debugout .= str_pad("including /lib/classes/HTML.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/HTML.class.php"))) ? $debugout .= "OK\n" :  $debugout .= "FAILED\n";

  $HTML = new HTML;



  // Other classes
  $debugout .= str_pad("including /lib/classes/Subscriber.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Subscriber.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";

  $debugout .= str_pad("including /lib/classes/Email.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Email.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";



  // init gettext()
  $debugout .= str_pad("including /lib/initGettext.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/initGettext.php"))) ? : $debugout .= "FAILED\n";



  // Database related
  $debugout .= str_pad("including /lib/bootstrap.database.common.php", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.database.common.php"))) ? : $debugout .= "FAILED\n";

  $debugout .= str_pad("including /lib/bootstrap.database.php", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.database.php"))) ? : $debugout .= "FAILED\n";

  // Database maintenance
  $debugout .= str_pad("including /lib/bootstrap.maintenance.php", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.maintenance.php"))) ? : $debugout .= "FAILED\n";


  $debugout .= "</pre>\n";

?>
