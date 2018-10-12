<?php

  $cbNewsletter_Debugout->add("<pre><b>[ error-reporting ]</b>");

// ================= php error reporting =================








if (isset(cbNewsletter_DIC::get("general")["debug"]) and cbNewsletter_DIC::get("general")["debug"]) {

    $cbNewsletter_Debugout->add("display_errors", "ON");
    ini_set("display_errors", 1);

  } else {

    $cbNewsletter_Debugout->add("display_errors", "OFF");
    ini_set("display_errors", 0);

  }

  error_reporting(cbNewsletter_DIC::get("general")["debug_level"]);

  $cbNewsletter_Debugout->add("log_errors", "ON");
  ini_set("log_errors", 1);

  $logTimeFormat = date("Y-m-d");
  $cbNewsletter_Debugout->add("error_log", cbNewsletter_DIC::get("basedir") . "/admin/logs/php-errors_" . $logTimeFormat . ".log");
  ini_set("error_log", cbNewsletter_DIC::get("basedir") . "/admin/logs/php-errors_" . $logTimeFormat . ".log");


// ================= php error reporting =================

$cbNewsletter_Debugout->add("</pre>");

?>
