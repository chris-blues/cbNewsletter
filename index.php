<?php

  $cbNewsletter = array();

  $cbNewsletter["startTime"] = microtime();

  $cbNewsletter["calldir"] = __DIR__;

  $cbNewsletter["config"]["basedir"] = dirname(__FILE__);

  include_once(__DIR__ . "/lib/classes/Debugout.class.php");
  $Debugout = new Debugout;

  include_once(__DIR__ . "/lib/checkout.function.php");



  $Debugout->add("<pre><b>[ index ]</b>");

  //include HTML header, if we're called directly
  if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {

    $Debugout->add("standalone mode?", "Yes");

    include_once(checkout("/views/header.php"));

  } else {

    $Debugout->add("standalone mode?", "No");

  }

?>

    <div id="cbNewsletter_mainBox">

<?php

  $Debugout->add(
    "loading \$cbNewsletter[\"config\"][\"general\"] from /admin/config/general.php",
    ($cbNewsletter["config"]["general"] = include_once(dirname(__FILE__) . "/admin/config/general.php")) ? "OK" : "FAILED"
  );

  $Debugout->add(
    "setting \$cbNewsletter[\"config\"][\"basedir\"] to", dirname(__FILE__),
    $cbNewsletter["config"]["basedir"] = dirname(__FILE__)
  );


  $debug = $cbNewsletter["config"]["general"]["debug"];






// override debug_level for php error messages [off|warn|full] (default: off)
//   $cbNewsletter["config"]["debug_level"] = "full";

// override debug messages by cbNewsletter
//   $debug = true;






  $Debugout->add("setting \$debug to ", ($debug ? "true" : "false"));

//   checkout("/lib/error-reporting.php");
  include_once(checkout("/lib/error-reporting.php"));



  include_once(checkout("/lib/bootstrap.php"));

  include_once(checkout("/lib/routing.php"));



  if ($debug and isset($error)) {
    cbNewsletter_showErrors($error);
  }


  $Debugout->add("</pre>");

  if ($debug) {

    echo $HTML->infobox("<h3>PHP debug output</h3>\n<p>$Debugout</p>\n" . $Debugout->output(), "debug");

  }


  if ($cbNewsletter["config"]["general"]["show_processing_time"]) {

    $cbNewsletter["endTime"] = microtime();

    echo $HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime($cbNewsletter["endTime"] - $cbNewsletter["startTime"])
      ),
      "notes center"
    );

  }

?>

    </div>

<?php

  if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    echo "\n  </body>\n</html>\n";
  }

?>
