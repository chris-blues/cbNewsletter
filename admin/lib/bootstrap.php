<?php

  $debugout .= "<pre><b>[ bootstrap ]</b>\n";

  date_default_timezone_set('Europe/Berlin');
  $debugout .= str_pad("date_default_timezone set to: ", 90) . date_default_timezone_get() . "\n";

  // General functions
  $debugout .= str_pad("including /lib/functions.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/functions.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";

  $debugout .= str_pad("including /lib/classes/HTML.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/HTML.class.php"))) ? $debugout .= "OK\n" :  $debugout .= "FAILED\n";

  $HTML = new HTML;

  $debugout .= str_pad("including /lib/initGettext.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/initGettext.php"))) ? : $debugout .= "FAILED\n";

  // HTML header
  $debugout .= str_pad("including /admin/views/header.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/header.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";


  if (!isset($_GET["view"]) or $_GET["view"] != "config") {

    // Other classes
    $debugout .= str_pad("including /lib/classes/Subscriber.class.php ", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Subscriber.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";

    // Database related
    $debugout .= str_pad("including /lib/bootstrap.database.common.php", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.database.common.php"))) ? : $debugout .= "FAILED\n";

    $debugout .= str_pad("including /admin/lib/bootstrap.database.php", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/bootstrap.database.php"))) ? : $debugout .= "FAILED\n";

    $debugout .= str_pad("including /lib/bootstrap.maintenance.php", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.maintenance.php"))) ? : $debugout .= "FAILED\n";

  } else {

    $debugout .= "skipping database bootstrapping...\n";

  }




  $debugout .= "</pre>";


?>
