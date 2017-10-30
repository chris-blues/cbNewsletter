<?php

  $debugout .= "<pre><b>[ config.action ]</b>\n";

  $needReload = false;

  if (isset($_POST["job"])) {

    $config = $_POST;

    unset($config["job"]);

    $debugout .= str_pad("including /admin/lib/classes/Config.class.php ", 90);
    $debugout .= (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/Config.class.php"))) ? "OK\n" : "FAILED\n";


    switch ($_POST["job"]) {

      case "update_dbSettings": {

        $debugout .= str_pad("Updating database settings...", 90);

        $ConfigFile = new Config("dbcredentials", $config);

        $result = $ConfigFile->save_dbSettings();

        if ($result === true) {

          $debugout .= "OK\n";
          $needReload = true;

        }
        else $debugout .= "FAILED\n";

        break;

      }

      case "update_generalSettings": {

        $debugout .= str_pad("Updating general settings...", 90);

        $configFile = new Config("general", $config);

        $result = $configFile->save_generalSettings();

        usleep(250000);

        while (@ob_end_flush());
        flush();

        if ($result === true) {

          $debugout .= "OK\n";
          $needReload = true;

          // reload config file
          unset($cbNewsletter["config"]["general"]);

          $debugout .= str_pad("reloading \$cbNewsletter[\"config\"][\"general\"] from /admin/config/general.php", 90);
          $cbNewsletter["config"]["general"]  = include(realpath($cbNewsletter["config"]["basedir"] . "/admin/config/general.php"));
          $debugout .= (count($cbNewsletter["config"]["general"]) > 0) ? "OK\n" : "FAILED\n";

        }
        else $debugout .= "FAILED\n";

        break;

      }

    }

    if ($result !== true) {
      $error["saveConfig"] = $result;
      $debugout .= "Errors:" . print_r($result, true) . "\n";
    }

  }


  // load database settings
  $debugout .= str_pad("loading \$cbNewsletter[\"config\"][\"database\"] from /admin/config/dbcredentials.php", 90);
  $cbNewsletter["config"]["database"] = include(realpath($cbNewsletter["config"]["basedir"] . "/admin/config/dbcredentials.php"));
  $debugout .= (count($cbNewsletter["config"]["database"]) > 0) ? "OK\n" : "FAILED\n";


  if ($needReload) {

    echo "<div class=\"center\"><h1>" . gettext("Will be back shortly...") . "</h1>\n<h2 id=\"timer\"></h2></div>\n";
    echo "<div class=\"hidden\" id=\"needReload\" data-link=\"" . assembleGetString("string") . "\"></div>\n</body>\n</html>\n";
    exit;

  }


  $debugout .= str_pad("including /admin/views/config.view.php ", 90);
  $debugout .= (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/config.view.php"))) ? "OK\n" : "FAILED\n";

  $debugout .= "</pre>\n";

?>
