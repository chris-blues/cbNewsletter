<?php

// ================  pre display  ================

  if (isset($_GET["job"]) and strlen($_GET["job"]) > 1) {

    switch ($_GET["job"]) {

      case "deleteUser": {

        if (isset($_GET["id"])) {
          $result = $query->removeSubscription($_GET["id"]);
	}

      break;

      }

    }

  }

// ================  pre display  ================





// ================  routing  ================

  if (isset($_GET["view"]) and strlen($_GET["view"]) > 1 and $_GET["view"] != "subscriptions") {

    include_once("views/" . $_GET["view"] . ".action.php");

  } else {

    include_once("views/subscriptions.php");

  }

// ================  routing  ================





// ================  post display  ================



// ================  post display  ================

?>
