<?php

  $config = $_POST;

  if (isset($_POST["job"])) {

    unset($config["job"]);

    include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/Config.class.php"));

    $output = "job =&gt; " . $_POST["job"] . ". ";

    switch ($_POST["job"]) {

      case "update_dbSettings": {

        $output .= "Updating database settings...";

        $ConfigFile = new Config("dbcredentials", $config);

        $result = $ConfigFile->save_dbSettings();

        if (!$result) $error["saveConfig"] = $result;

        break;

      }

      if (!isset($error)) $output .= " <span class=\"green\">✔</span>";
      else $output .= "Error(s):<pre class=\"errors\">" . print_r($error, true) . "</pre>";

    }

    if ($debug) echo $HTML->infobox($output, "debug");

  }

  $cbNewsletter["config"]["database"] = include(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/dbcredentials.php"));

  $tmp = $cbNewsletter["config"]["database"];

  foreach ($tmp as $key => $value) {

    if ($key == "tables" or $key == "options") unset($tmp[$key]);

  }

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/config.view.php"));

?>
