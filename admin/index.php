<?php

  $cbNewsletter_startTime = microtime();

  $cbNewsletter["config"] = include_once(realpath(dirname(__FILE__) . "/lib/config.php"));

  $cbNewsletter["config"]["basedir"] = dirname(__FILE__);



  //load config
  $cbNewsletter["config"]["error_reporting"] = $cbNewsletter["config"]["debug_levels"][$cbNewsletter["config"]["debug_level"]];

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/../lib/error-reporting.php"));

  $debug = $cbNewsletter["config"]["debug"];

  // override debug_level for php error messages [off|warn|full] (default: off)
  //   $cbNewsletter["config"]["debug_level"] = "full";

  // override debug messages by cbNewsletter
  $debug = true;


  include_once(realpath(dirname(__FILE__) . "/lib/bootstrap.php"));


  include_once("lib/routing.php");

  if (isset($error)) {
    cbNewsletter_showErrors($error);
  }



  $cbNewsletter_endTime = microtime();

  $HTML->infobox(
    sprintf(
      gettext("processing needed %s"),
      prettyTime($cbNewsletter_endTime - $cbNewsletter_startTime)
    ),
    "notes center"
  );

?>

  </body>
</html>
