<?php

  $cbNewsletter_startTime = microtime();

  $cbNewsletter["calldir"] = __DIR__;
  $debugout = "";

  if (isset($_GET) and count($_GET) > 0) {
    $debugout = "<pre><b>\$_GET</b> ";
    $debugout .= print_r($_GET, true) . "</pre>\n";
  }
  if (isset($_POST) and count($_POST) > 0) {
    $debugout .= "<pre><b>\$_POST</b> ";
    $debugout .= print_r($_POST, true) . "</pre>\n";
  }

  $debugout .= "<pre><b>[ index ]</b>\n";

  $debugout .= str_pad("standalone mode?", 90);
  //include HTML header, if we're called directly
  if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {

    $debugout .= "Yes! -&gt including /views/header.php\n";
    include_once(realpath(dirname(__FILE__) . "/views/header.php"));

  } else {

    $debugout .= "No.\n";

  }

?>

    <div id="cbNewsletter_mainBox">

<?php

  $debugout .= str_pad("loading \$cbNewsletter[\"config\"][\"general\"] from /admin/config/general.php", 90);
  ($cbNewsletter["config"]["general"] = include_once(realpath(dirname(__FILE__) . "/admin/config/general.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";

  $debugout .= str_pad("setting \$cbNewsletter[\"config\"][\"basedir\"] to ", 90) . realpath(dirname(__FILE__) . "/../") . "\n";
  $cbNewsletter["config"]["basedir"] = dirname(__FILE__);


  $debug = $cbNewsletter["config"]["general"]["debug"];






// override debug_level for php error messages [off|warn|full] (default: off)
//   $cbNewsletter["config"]["debug_level"] = "full";

// override debug messages by cbNewsletter
//   $debug = true;


  $debugout .= str_pad("setting \$debug to ", 90) . ($debug ? "true" : "false") . "\n";

  $debugout .= str_pad("including /lib/error-reporting.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/error-reporting.php"))) ? : $debugout .= "FAILED\n";



  $debugout .= str_pad("including /lib/bootstrap.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/bootstrap.php"))) ? : $debugout .= "FAILED\n";

  $debugout .= str_pad("including /lib/routing.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/routing.php"))) ? : $debugout .= "FAILED\n";



  if ($debug and isset($error)) {
    cbNewsletter_showErrors($error);
  }


  $debugout .= "</pre>\n";

  if ($debug) {

    echo $HTML->infobox("<h3>debug output [ PHP ]</h3>\n<pre>" . $debugout . "</pre>", "debug");

  }


  if ($cbNewsletter["config"]["general"]["show_processing_time"]) {

    $cbNewsletter_endTime = microtime();

    echo $HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime($cbNewsletter_endTime - $cbNewsletter_startTime)
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
