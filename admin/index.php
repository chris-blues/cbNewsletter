<?php

  include_once(realpath(__DIR__ . "/../lib/classes/DIC.class.php"));

  DIC::add("startTime", microtime());

  DIC::add("calldir", __DIR__);

  include_once(DIC::get("calldir") . "/../lib/classes/Debugout.class.php");
  $Debugout = new Debugout;

  include_once(DIC::get("calldir") . "/../lib/checkout.function.php");



  $Debugout->add("<pre><b>[ index ]</b>");

  // set basedir to /path/to/newsletter/ , not /path/to/newsletter/admin
  DIC::add("basedir", realpath(DIC::get("calldir") . "/../"));


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




  include_once(checkout("/admin/lib/bootstrap.php"));

  include_once(checkout("/admin/lib/routing.php"));






  $Debugout->add("</pre>");

  if ($debug) {

    echo $HTML->infobox("<h3>debug output</h3>\n<p>$Debugout</p>\n" . $Debugout->output(), "debug");

  }

  if (isset($error)) {

    include_once(checkout("/lib/error-reporting.php"));

    cbNewsletter_showErrors($error);

  }

  if (isset($error)) {
    $logTimeFormat = date("Y-m-d");
    file_put_contents(
      DIC::get("basedir") . "/admin/logs/debug_" . $logTimeFormat . ".log",
      "\n\n=============================================================================================================================\n" . date("Y-m-d H:i:s") . "\n\n" . $Debugout->output(true),
      FILE_APPEND | LOCK_EX
    );
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

  </body>
</html>
