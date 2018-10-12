<?php

  include_once(realpath(__DIR__ . "/../lib/classes/DIC.class.php"));

  cbNewsletter_DIC::add("startTime", microtime());

  cbNewsletter_DIC::add("calldir", __DIR__);

  include_once(cbNewsletter_DIC::get("calldir") . "/../lib/classes/Debugout.class.php");
  $cbNewsletter_Debugout = new cbNewsletter_Debugout;

  include_once(cbNewsletter_DIC::get("calldir") . "/../lib/checkout.function.php");



  $cbNewsletter_Debugout->add("<pre><b>[ index ]</b>");

  // set basedir to /path/to/newsletter/ , not /path/to/newsletter/admin
  cbNewsletter_DIC::add("basedir", realpath(cbNewsletter_DIC::get("calldir") . "/../"));


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




  include_once(cbNewsletter_checkout("/admin/lib/bootstrap.php"));

  include_once(cbNewsletter_checkout("/admin/lib/routing.php"));






  $cbNewsletter_Debugout->add("</pre>");

  if ($debug) {

    echo $cbNewsletter_HTML->infobox("<h3>debug output</h3>\n<p>$cbNewsletter_Debugout</p>\n" . $cbNewsletter_Debugout->output(), "debug");

  }

  if (isset($error)) {

    include_once(cbNewsletter_checkout("/lib/error-reporting.php"));

    cbNewsletter_showErrors($error);

  }

  if (isset($error)) {
    $logTimeFormat = date("Y-m-d");
    file_put_contents(
      cbNewsletter_DIC::get("basedir") . "/admin/logs/debug_" . $logTimeFormat . ".log",
      "\n\n=============================================================================================================================\n" . date("Y-m-d H:i:s") . "\n\n" . $cbNewsletter_Debugout->output(true),
      FILE_APPEND | LOCK_EX
    );
  }


  if (cbNewsletter_DIC::get("general")["show_processing_time"]) {

    cbNewsletter_DIC::add("endTime", microtime());

    echo $cbNewsletter_HTML->infobox(
      sprintf(
        gettext("processing needed %s"),
        prettyTime(cbNewsletter_DIC::get("endTime") - cbNewsletter_DIC::get("startTime"))
      ),
      "notes center"
    );

  }

?>

  </body>
</html>
