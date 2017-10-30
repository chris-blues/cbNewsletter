<?php

  $cbNewsletter_startTime = microtime();

  $debugout = "";

  if (isset($_GET) and count($_GET) > 0) {
    $debugout .= "<pre><b>\$_GET</b> ";
    $debugout .= print_r($_GET, true) . "</pre>\n";
  }
  if (isset($_POST) and count($_POST) > 0) {
    $debugout .= "<pre><b>\$_POST</b> ";
    $debugout .= print_r($_POST, true) . "</pre>\n";
  }

  $debugout .= "<pre><b>[ index ]</b>\n";

  $debugout .= str_pad("setting \$cbNewsletter[\"config\"][\"basedir\"] to ", 90) . realpath(dirname(__FILE__) . "/../") . "\n";
  // set basedir to /path/to/newsletter/ , not /path/to/newsletter/admin
  $cbNewsletter["config"]["basedir"] = realpath(dirname(__FILE__) . "/../");


  //load config
  $debugout .= str_pad("loading \$cbNewsletter[\"config\"][\"general\"] from /admin/config/general.php: ", 90);
  $cbNewsletter["config"]["general"] = include(realpath($cbNewsletter["config"]["basedir"] . "/admin/config/general.php"));
  if (count($cbNewsletter["config"]["general"]) > 0)
       $debugout .= "OK\n";
  else $debugout .= "FAILED\n";

  $debugout .= str_pad("including /lib/error-reporting.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/error-reporting.php"))) ? : $debugout .= "FAILED\n";

  $debug = $cbNewsletter["config"]["general"]["debug"];

  // override debug_level for php error messages [off|warn|full] (default: off)
  //   $cbNewsletter["config"]["general"]["debug_level"] = "full";

  // override debug messages by cbNewsletter
  //   $debug = true;

  $debugout .= str_pad("setting \$debug to ", 90);
  $debugout .= $debug ? "true" : "false";
  $debugout .= "\n";

  $debugout .= str_pad("including /admin/lib/bootstrap.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/bootstrap.php"))) ? : $debugout .= "FAILED\n";


  $debugout .= str_pad("including /admin/lib/routing.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/routing.php"))) ? : $debugout .= "FAILED\n";


  $debugout .= "</pre>\n";

  if ($debug) echo $HTML->infobox("<h3>debug output [ PHP ]</h3>\n<pre>" . $debugout . "</pre>", "debug");

  if (isset($error)) {
    cbNewsletter_showErrors($error);
  }



  $cbNewsletter_endTime = microtime();

  if ($cbNewsletter["config"]["general"]["show_processing_time"]) {

    echo $HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime($cbNewsletter_endTime - $cbNewsletter_startTime)
      ),
      "notes center"
    );
  }

?>

  </body>
</html>
