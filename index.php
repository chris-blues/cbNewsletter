<?php

  //include HTML header, if we're called directly
  if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    include_once(realpath(dirname(__FILE__) . "/views/header.php"));
  }


  $cbNewsletter_startTime = microtime();

  $cbNewsletter["config"] = include_once(realpath(dirname(__FILE__) . "/admin/lib/config.php"));

  $cbNewsletter["config"]["basedir"] = dirname(__FILE__);

  $debug = $cbNewsletter["config"]["debug"];






// override debug_level for php error messages [off|warn|full] (default: off)
//   $cbNewsletter["config"]["debug_level"] = "full";

// override debug messages by cbNewsletter
//   $debug = true;




  $cbNewsletter["config"]["error_reporting"] = $cbNewsletter["config"]["debug_levels"][$cbNewsletter["config"]["debug_level"]];






  include_once($cbNewsletter["config"]["basedir"] . "/lib/error-reporting.php");

  include_once($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.php");

  include_once($cbNewsletter["config"]["basedir"] . "/lib/routing.php");


  if (isset($error)) {
    cbNewsletter_showErrors($error);
  }




  if ($debug) {

    if (isset($error)) {

      echo "<h2>Errors:</h2><pre class=\"errors\">"; dump_var($error); echo "</pre>\n";

    } else {

      echo "<pre>No errors detected</pre>";

    }

  }


  if ($cbNewsletter["config"]["show_processing_time"]) {

    $cbNewsletter_endTime = microtime();

    $HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime($cbNewsletter_endTime - $cbNewsletter_startTime)
      ),
      "notes center"
    );

  }

  if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    echo "\n  </body>\n</html>\n";
  }

?>
