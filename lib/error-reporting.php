<?php

  $debugout .= "<pre><b>[ error-reporting ]</b>\n";

// ================= php error reporting =================

if (isset($_GET["debug"]) and ($_GET["debug"] == "TRUE" or $_GET["debug"] == "true")) $cbNewsletter["config"]["general"]["debug"] = true;
if (isset($_POST["debug"]) and ($_POST["debug"] == "TRUE" or $_POST["debug"] == "true")) $cbNewsletter["config"]["general"]["debug"] = true;



  $debugout .= str_pad("setting \$cbNewsletter[\"config\"][\"general\"][\"error_reporting\"] to ", 90) . array_search(
    $cbNewsletter["config"]["general"]["debug_levels"][$cbNewsletter["config"]["general"]["debug_level"]],
    $cbNewsletter["config"]["general"]["debug_levels"]
  ) . "\n";
  $cbNewsletter["config"]["general"]["error_reporting"] = $cbNewsletter["config"]["general"]["debug_levels"][$cbNewsletter["config"]["general"]["debug_level"]];



if (isset($cbNewsletter["config"]["general"]["debug"]) and $cbNewsletter["config"]["general"]["debug"]) {

    $debugout .= str_pad("display_errors", 90) . "ON\n";
    ini_set("display_errors", 1);

  } else {

    $debugout .= str_pad("display_errors", 90) . "OFF\n";
    ini_set("display_errors", 0);

  }

  error_reporting($cbNewsletter["config"]["general"]["error_reporting"]);

  $debugout .= str_pad("log_errors", 90) . "ON\n";
  ini_set("log_errors", 1);

  $debugout .= str_pad("error_log", 90) . $cbNewsletter["config"]["basedir"] . "/php-errors.log\n";
  ini_set("error_log", $cbNewsletter["config"]["basedir"] . "/php-errors.log");

//   $debug = $cbNewsletter["config"]["general"]["debug"];


// ================= php error reporting =================

$debugout .= "</pre>\n";

?>
