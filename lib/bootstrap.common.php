<?php

  $Debugout->add("<pre><b>[ bootstrap.common ]</b>");

  date_default_timezone_set('Europe/Berlin');
  $Debugout->add(
    "date_default_timezone set to:",
    date_default_timezone_get()
  );



  // General functions
  include_once(checkout("/lib/functions.php"));

  include_once(checkout("/lib/classes/HTML.class.php"));
  $HTML = new HTML;


  // Other classes
  include_once(checkout("/lib/classes/Subscriber.class.php"));


  // gettext
  include_once(checkout("/lib/initGettext.php"));


  if (!isset($_GET["view"]) or $_GET["view"] != "config") {

    // Database related
    include_once(checkout("/lib/bootstrap.database.common.php"));


    $subdir = str_replace($cbNewsletter["config"]["basedir"], "", $cbNewsletter["calldir"]);

    include_once(checkout($subdir . "/lib/bootstrap.database.php"));

    include_once(checkout("/lib/bootstrap.maintenance.php"));

  } else {

    $Debugout->add("skipping database bootstrapping...");

  }




  $Debugout->add("</pre>");


?>
