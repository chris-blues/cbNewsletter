<?php

  $Debugout->add("<pre><b>[ error-reporting ]</b>");

// ================= php error reporting =================








if (isset(DIC::get("general")["debug"]) and DIC::get("general")["debug"]) {

    $Debugout->add("display_errors", "ON");
    ini_set("display_errors", 1);

  } else {

    $Debugout->add("display_errors", "OFF");
    ini_set("display_errors", 0);

  }

  error_reporting(DIC::get("general")["debug_level"]);

  $Debugout->add("log_errors", "ON");
  ini_set("log_errors", 1);

  $logTimeFormat = date("Y-m-d");
  $Debugout->add("error_log", DIC::get("basedir") . "/admin/logs/php-errors_" . $logTimeFormat . ".log");
  ini_set("error_log", DIC::get("basedir") . "/admin/logs/php-errors_" . $logTimeFormat . ".log");


// ================= php error reporting =================

$Debugout->add("</pre>");

?>
