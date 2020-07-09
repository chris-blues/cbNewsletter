<?php

  $cbNewsletter_Debugout->add("<pre><b>[ config.action ]</b>");

  $needReload = false;

  if (isset($_POST["job"])) {

    $config = $_POST;

    unset($config["job"]);

    include_once(cbNewsletter_checkout("/admin/lib/classes/Config.class.php"));


    switch ($_POST["job"]) {

      case "update_dbSettings": {

        $ConfigFile = new Config("dbcredentials", $config);

        $result = $ConfigFile->save_dbSettings();

        if ($result === true) {

          $result = "OK";
          $needReload = true;

        }
        else {
            $error["save_dbSettings"] = $result;
            $result = "FAILED";
        }

        $cbNewsletter_Debugout->add("Updating database settings...", $result);

        break;

      }

      case "update_generalSettings": {

        $configFile = new Config("general", $config);

        $result = $configFile->save_generalSettings();

        if ($result === true) {

          $cbNewsletter_Debugout->add("Updating general settings...", "OK");
          $needReload = true;

          // reload config file
          cbNewsletter_DIC::unset("general");

          cbNewsletter_DIC::add("general", include(realpath(cbNewsletter_DIC::get("basedir") . "/admin/config/general.php")));
          $cbNewsletter_Debugout->add(
            "reloading cbNewsletter_DIC::[\"general\"] from /admin/config/general.php",
            (count(cbNewsletter_DIC::get("general")) > 0) ? "OK" : "FAILED"
          );

        }
        else $cbNewsletter_Debugout->add("Updating general settings...", "FAILED");

        break;

      }

    }

    if ($result !== true) {

      $error["saveConfig"] = $result;
      $cbNewsletter_Debugout->add("Errors:" . print_r($result, true));

    }

  }


  // load database settings
  cbNewsletter_DIC::add("database", include(cbNewsletter_DIC::get("basedir") . "/admin/config/dbcredentials.php"));
  $cbNewsletter_Debugout->add(
    "loading cbNewsletter_DIC::[\"database\"] from /admin/config/dbcredentials.php",
    (!empty(cbNewsletter_DIC::get("database"))) ? "OK" : "FAILED"
  );


  if ($needReload) {

    echo "<div class=\"center\"><h1>" . gettext("Will be back shortly...") . "</h1>\n<h2 id=\"timer\"></h2></div>\n";
    echo "<div class=\"hidden\" id=\"needReload\" data-link=\"" . assembleGetString("string") . "\"></div>\n</body>\n</html>\n";
    exit;

  }


  include_once(cbNewsletter_checkout("/admin/views/config.view.php"));

  $cbNewsletter_Debugout->add("</pre>");

?>
