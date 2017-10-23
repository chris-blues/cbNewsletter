<?php

// ================= php error reporting =================

if (isset($_GET["debug"]) and ($_GET["debug"] == "TRUE" or $_GET["debug"] == "true")) $cbNewsletter["config"]["debug"] = true;
if (isset($_POST["debug"]) and ($_POST["debug"] == "TRUE" or $_POST["debug"] == "true")) $cbNewsletter["config"]["debug"] = true;



if (isset($cbNewsletter["config"]["debug"]) and $cbNewsletter["config"]["debug"]) {

    ini_set("display_errors", 1);

  } else {

    ini_set("display_errors", 0);

  }

  error_reporting($cbNewsletter["config"]["error_reporting"]);

  ini_set("log_errors", 1);
  ini_set("error_log", $cbNewsletter["config"]["basedir"] . "/php-errors.log");

  $debug = $cbNewsletter["config"]["debug"];

  if ($debug) {
    echo "<b>[ Debug mode ]</b> logging php-errors to <i>" . ini_get("error_log") . "</i><br>\n";
  }


// ================= php error reporting =================

?>
