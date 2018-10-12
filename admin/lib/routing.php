<?php

  $cbNewsletter_Debugout->add("<pre><b>[ routing ]</b>");

// ================  pre display  ================

  if (isset($_GET["job"]) and strlen($_GET["job"]) > 1) {

    switch ($_GET["job"]) {

      case "deleteUser": {

        if (isset($_GET["id"])) {

          $result = $cbNewsletter_query->removeSubscription($_GET["id"]);
          $cbNewsletter_Debugout->add(
            "\$_GET[\"job\"] => " . $_GET["job"] . " -> \$cbNewsletter_query-&gt;removeSubscription(" . $_GET["id"] . ")",
            ($result) ? "OK" : "FAILED"
          );
	}

      break;

      }

      case "save_template": {

        $result = $cbNewsletter_query->add_template($_POST);
        $cbNewsletter_Debugout->add(
          "adding template " . $_POST["name"],
          ($result) ? "OK" : "FAILED"
        );

        break;

      }

      case "delete_template": {

        $result = $cbNewsletter_query->delete_template($_GET["id"]);
        $cbNewsletter_Debugout->add(
          "deleting template " . $_GET["id"],
          ($result) ? "OK" : "FAILED"
        );

        break;

      }

    }

  }

// ================  pre display  ================





// =================== Routing ===================


  include_once(cbNewsletter_checkout(
    cbNewsletter_Router::load("/admin/lib/routes.php")
      ->direct(cbNewsletter_DIC::get("view"))
  ));


// =================== Routing ===================





// ================  post display  ================



// ================  post display  ================

  $cbNewsletter_Debugout->add("</pre>");

?>
