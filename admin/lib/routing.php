<?php

  $Debugout->add("<pre><b>[ routing ]</b>");

// ================  pre display  ================

  if (isset($_GET["job"]) and strlen($_GET["job"]) > 1) {

    switch ($_GET["job"]) {

      case "deleteUser": {

        if (isset($_GET["id"])) {

          $result = $query->removeSubscription($_GET["id"]);
          $Debugout->add(
            "\$_GET[\"job\"] => " . $_GET["job"] . " -> \$query-&gt;removeSubscription(" . $_GET["id"] . ")",
            ($result) ? "OK" : "FAILED"
          );
	}

      break;

      }

      case "save_template": {

        $result = $query->add_template($_POST);
        $Debugout->add(
          "adding template " . $_POST["name"],
          ($result) ? "OK" : "FAILED"
        );

        break;

      }

      case "delete_template": {

        $result = $query->delete_template($_GET["id"]);
        $Debugout->add(
          "deleting template " . $_GET["id"],
          ($result) ? "OK" : "FAILED"
        );

        break;

      }

    }

  }

// ================  pre display  ================





// =================== Routing ===================


  include_once(checkout(
    Router::load("/admin/lib/routes.php")
      ->direct($cbNewsletter["config"]["view"])
  ));


// =================== Routing ===================





// ================  post display  ================



// ================  post display  ================

  $Debugout->add("</pre>");

?>
