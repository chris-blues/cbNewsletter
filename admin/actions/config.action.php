<?php

  $Debugout->add("<pre><b>[ config.action ]</b>");

  $needReload = false;

  if (isset($_POST["job"])) {

    $config = $_POST;

    unset($config["job"]);

    include_once(checkout("/admin/lib/classes/Config.class.php"));


    switch ($_POST["job"]) {

      case "update_dbSettings": {

        $ConfigFile = new Config("dbcredentials", $config);

        $result = $ConfigFile->save_dbSettings();

        if ($result === true) {

          $result = "OK";
          $needReload = true;

        }
        else $result = "FAILED";

        $Debugout->add("Updating database settings...", $result);

        break;

      }

      case "update_generalSettings": {

        $configFile = new Config("general", $config);

        $result = $configFile->save_generalSettings();

        if ($result === true) {

          $Debugout->add("Updating general settings...", "OK");
          $needReload = true;

          // reload config file
          DIC::unset("general");

          DIC::add("general", include(realpath(DIC::get("basedir") . "/admin/config/general.php")));
          $Debugout->add(
            "reloading DIC::[\"general\"] from /admin/config/general.php",
            (count(DIC::get("general")) > 0) ? "OK" : "FAILED"
          );

        }
        else $Debugout->add("Updating general settings...", "FAILED");

        break;

      }

    }

    if ($result !== true) {

      $error["saveConfig"] = $result;
      $Debugout->add("Errors:" . print_r($result, true));

    }

  }


  // load database settings
  DIC::add("database", include(DIC::get("basedir") . "/admin/config/dbcredentials.php"));
  $Debugout->add(
    "loading DIC::[\"database\"] from /admin/config/dbcredentials.php",
    (count(DIC::get("database")) > 0) ? "OK" : "FAILED"
  );


  if ($needReload) {

    echo "<div class=\"center\"><h1>" . gettext("Will be back shortly...") . "</h1>\n<h2 id=\"timer\"></h2></div>\n";
    echo "<div class=\"hidden\" id=\"needReload\" data-link=\"" . assembleGetString("string") . "\"></div>\n</body>\n</html>\n";
    exit;

  }


  include_once(checkout("/admin/views/config.view.php"));

  $Debugout->add("</pre>");

?>
