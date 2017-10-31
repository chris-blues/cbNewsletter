<?php

  $Debugout->add("<pre><b>[ error-reporting ]</b>");

// ================= php error reporting =================

if (isset($_GET["debug"]) and ($_GET["debug"] == "TRUE" or $_GET["debug"] == "true")) {

  $cbNewsletter["config"]["general"]["debug"] = true;

}
if (isset($_POST["debug"]) and ($_POST["debug"] == "TRUE" or $_POST["debug"] == "true")) {

  $cbNewsletter["config"]["general"]["debug"] = true;

}



  $Debugout->add(
    "setting \$cbNewsletter[\"config\"][\"general\"][\"error_reporting\"] to ",
    array_search(
      $cbNewsletter["config"]["general"]["debug_levels"][
        $cbNewsletter["config"]["general"]["debug_level"]
      ],
      $cbNewsletter["config"]["general"]["debug_levels"]
    )
  );
  $cbNewsletter["config"]["general"]["error_reporting"] = $cbNewsletter["config"]["general"]["debug_levels"][$cbNewsletter["config"]["general"]["debug_level"]];



if (isset($cbNewsletter["config"]["general"]["debug"]) and $cbNewsletter["config"]["general"]["debug"]) {

    $Debugout->add("display_errors", "ON");
    ini_set("display_errors", 1);

  } else {

    $Debugout->add("display_errors", "OFF");
    ini_set("display_errors", 0);

  }

  error_reporting($cbNewsletter["config"]["general"]["error_reporting"]);

  $Debugout->add("log_errors", "ON");
  ini_set("log_errors", 1);

  $Debugout->add("error_log", $cbNewsletter["config"]["basedir"] . "/php-errors.log");
  ini_set("error_log", $cbNewsletter["config"]["basedir"] . "/php-errors.log");


// ================= php error reporting =================

$Debugout->add("</pre>");

?>
