<?php

  include_once(__DIR__ . "/lib/classes/DIC.class.php");

  DIC::add("startTime", microtime());


  // catch possible variable collisions
  if (isset($debug))  $previous["debug"]  = $debug;
  if (isset($lang))   $previous["lang"]   = $lang;
  if (isset($locale)) $previous["locale"] = $locale;

  if (isset($previous)) {

    DIC::add("previous_variables", $previous);
    DIC::add("previous_variables_switch", true);

  } else {

    DIC::add("previous_variables_switch", false);

  }


  DIC::add("calldir", __DIR__);

  include_once(DIC::get("calldir") . "/lib/classes/Debugout.class.php");
  $Debugout = new Debugout;

  $Debugout->add("<pre><b>[ index ]</b>");

  DIC::add("basedir", DIC::get("calldir"));

  include_once(DIC::get("basedir") . "/lib/checkout.function.php");




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

  //load config
  DIC::add(
    "general",
    include_once(DIC::get("basedir") . "/admin/config/general.php")
  );

  if (count(DIC::get("general")) <= 1) {

    $Debugout->add("loading general config from /admin/config/general.php", "FAILED");

    DIC::add("general", include_once(checkout("/lib/config.default.php")));

  } else {

    $Debugout->add("loading general config from /admin/config/general.php", "OK");

  }


  $debug = DIC::get("general")["debug"];

  $Debugout->add("setting \$debug to ", ($debug ? "true" : "false"));





  include_once(checkout("/lib/bootstrap.php"));

  include_once(checkout("/lib/routing.php"));



  if (isset($error)) {

    include_once(checkout("/lib/error-reporting.php"));

    cbNewsletter_showErrors($error);

  }


  $Debugout->add("</pre>");

  if (isset($error)) {

    $logTimeFormat = date("Y-m-d");
    file_put_contents(
      DIC::get("basedir") . "/admin/logs/debug_" . $logTimeFormat . ".log",
      "\n\n=============================================================================================================================\n" . date("Y-m-d H:i:s") . "\n\n" . $Debugout->output(true),
      FILE_APPEND | LOCK_EX
    );

  }

  if ($debug) {

    echo $HTML->infobox("<h3>debug output</h3>\n<p>$Debugout</p>\n" . $Debugout->output(), "debug");

  }


  if (DIC::get("general")["show_processing_time"]) {

    DIC::add("endTime", microtime());

    echo $HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime(DIC::get("endTime") - DIC::get("startTime"))
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

  if (DIC::get("previous_variables_switch")) {

    foreach (DIC::get("previous_variables") as $key => $value) {

      $$key = $value;

    }

  }

?>
