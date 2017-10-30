<?php

  $debugout .= "<pre><b>[ routing ]</b>\n";

// ================  pre display  ================

  if (isset($_GET["job"]) and strlen($_GET["job"]) > 1) {

    switch ($_GET["job"]) {

      case "deleteUser": {

        if (isset($_GET["id"])) {
          $debugout .= str_pad("\$_GET[\"job\"] => " . $_GET["job"] . " -> \$query-&gt;removeSubscription(" . $_GET["id"] . ") : ", 90);
          $result = $query->removeSubscription($_GET["id"]);
          $debugout .= ($result) ? "OK\n" : "FAILED\n";
	}

      break;

      }

    }

  }

// ================  pre display  ================





// ================  routing  ================

  if (isset($_GET["view"]) and strlen($_GET["view"]) > 1 and $_GET["view"] != "subscriptions") {

    $debugout .= str_pad("including /admin/actions/" . $_GET["view"] . ".action.php ", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/actions/" . $_GET["view"] . ".action.php"))) ? : $debugout .= "FAILED\n";

  } else {

    $debugout .= str_pad("including /admin/actions/subscriptions.action.php ", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/actions/subscriptions.action.php"))) ? : $debugout .= "FAILED\n";

  }

// ================  routing  ================





// ================  post display  ================



// ================  post display  ================

  $debugout .= "</pre>";

?>
