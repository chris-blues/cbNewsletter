<h3><?php echo gettext("verify unsubscription"); ?></h3>

<?php

  $debugout .= "<pre><b>[ verify_unsubscription ]</b>\n";

  $debugout .= str_pad("including /lib/verify.transmitted_data.php", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"))) ? : $debugout .= "FAILED\n";



// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    $result = $query->check_subscription($data["id"], $data["hash"]);

    $debugout .= str_pad("verifying data against the database", 90);

    if (intval($result) === 1) {

// ============  verify data against database  ============

      $debugout .= "passed\n";

      $subscriber = $query->getSubscriberData($data["id"]);
      $subscriber[0]->correctTypes();
      $db_data = $subscriber[0]->getdata();

      $result = $query->removeSubscription($data["id"]);

      $debugout .= str_pad("removing subscription for " . $db_data["email"], 90);
      if ($result) {

        echo $HTML->infobox(
          sprintf(
            gettext("Removing subscription for %s"), $db_data["email"]
	  ) . " : <span class=\"green\">✔</span>"
	);
	$debugout .= "OK\n";

      } else {

        $error["database"]["removeSubscription"]["error"] = true;
        $error["database"]["removeSubscription"]["data"] = $result;

        echo $HTML->errorbox(sprintf(gettext("Removing subscription for %s failed!"), $db_data["email"]) . "<br>\n" . $result);

        $debugout .= "FAILED\n";

      }

    } else {

      $error["database"]["error"] = true;
      $error["database"]["subscriber_not_exists"] = "This subscription can not be found!";

      echo $HTML->errorbox(gettext("Sorry! The link seems to be broken! Please try again - and make sure you have the complete link!<br>\n"));

      $debugout .= "FAILED\n";

    }

  }




?>
