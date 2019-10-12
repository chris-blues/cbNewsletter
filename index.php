<?php

  include_once(__DIR__ . "/lib/classes/DIC.class.php");

  cbNewsletter_DIC::add("startTime", microtime(true));


  // catch possible variable collisions
  if (isset($debug))  $previous["debug"]  = $debug;
  if (isset($lang))   $previous["lang"]   = $lang;
  if (isset($locale)) $previous["locale"] = $locale;

  if (isset($previous)) {

    cbNewsletter_DIC::add("previous_variables", $previous);
    cbNewsletter_DIC::add("previous_variables_switch", true);

  } else {

    cbNewsletter_DIC::add("previous_variables_switch", false);

  }


  cbNewsletter_DIC::add("calldir", __DIR__);

  include_once(cbNewsletter_DIC::get("calldir") . "/lib/classes/Debugout.class.php");
  $cbNewsletter_Debugout = new cbNewsletter_Debugout;

  $cbNewsletter_Debugout->add("<pre><b>[ index ]</b>");

  cbNewsletter_DIC::add("basedir", cbNewsletter_DIC::get("calldir"));

  include_once(cbNewsletter_DIC::get("basedir") . "/lib/checkout.function.php");




  //include HTML header, if we're called directly
  if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {

    $cbNewsletter_Debugout->add("standalone mode?", "Yes");

    include_once(cbNewsletter_checkout("/views/header.php"));

  } else {

    $cbNewsletter_Debugout->add("standalone mode?", "No");

  }

?>

    <div id="cbNewsletter_mainBox">

<?php

  //load config
  cbNewsletter_DIC::add(
    "general",
    include_once(cbNewsletter_DIC::get("basedir") . "/admin/config/general.php")
  );

  if (count(cbNewsletter_DIC::get("general")) <= 1) {

    $cbNewsletter_Debugout->add("loading general config from /admin/config/general.php", "FAILED");

    cbNewsletter_DIC::add("general", include_once(cbNewsletter_checkout("/lib/config.default.php")));

  } else {

    $cbNewsletter_Debugout->add("loading general config from /admin/config/general.php", "OK");

  }


  $debug = cbNewsletter_DIC::get("general")["debug"];

  $cbNewsletter_Debugout->add("setting \$debug to ", ($debug ? "true" : "false"));





  include_once(cbNewsletter_checkout("/lib/bootstrap.php"));

  include_once(cbNewsletter_checkout("/lib/routing.php"));



  if (isset($error)) {

    include_once(cbNewsletter_checkout("/lib/error-reporting.php"));

    cbNewsletter_showErrors($error);

  }


  $cbNewsletter_Debugout->add("</pre>");

  if (isset($error)) {

    $logTimeFormat = date("Y-m-d");
    file_put_contents(
      cbNewsletter_DIC::get("basedir") . "/admin/logs/debug_" . $logTimeFormat . ".log",
      "\n\n=============================================================================================================================\n" . date("Y-m-d H:i:s") . "\n\n" . $cbNewsletter_Debugout->output(true) . "\n\n" . print_r($error, true),
      FILE_APPEND | LOCK_EX
    );

  }

  if ($debug) {

    echo $cbNewsletter_HTML->infobox("<h3>debug output</h3>\n<p>$cbNewsletter_Debugout</p>\n" . $cbNewsletter_Debugout->output(), "debug");

  }


  if (cbNewsletter_DIC::get("general")["show_processing_time"]) {

    cbNewsletter_DIC::add("endTime", microtime(true));

    echo $cbNewsletter_HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime(cbNewsletter_DIC::get("endTime") - cbNewsletter_DIC::get("startTime"))
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

  if (cbNewsletter_DIC::get("previous_variables_switch")) {

    foreach (cbNewsletter_DIC::get("previous_variables") as $key => $value) {

      $$key = $value;

    }

  }

?>
