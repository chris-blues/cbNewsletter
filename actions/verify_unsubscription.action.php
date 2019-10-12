<h3><?php echo gettext("verify unsubscription"); ?></h3>

<?php

  $cbNewsletter_Debugout->add("<pre><b>[ verify_unsubscription ]</b>");

  include_once(cbNewsletter_checkout("/lib/verify.transmitted_data.php"));



// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    $result = $cbNewsletter_query->check_subscription($data["id"], $data["hash"]);

    if (intval($result) === 1) {

// ============  verify data against database  ============

      $cbNewsletter_Debugout->add("verifying data against the database", "passed");

      $subscriber = $cbNewsletter_query->getSubscriberData($data["id"]);
      $subscriber[0]->correctTypes();
      $db_data = $subscriber[0]->getdata();

      $result = $cbNewsletter_query->removeSubscription($data["id"]);

      if ($result) {

        echo $cbNewsletter_HTML->infobox(
          sprintf(
            gettext("Removing subscription for %s"), $db_data["email"]
	  ) . " : <span class=\"green\">✔</span>"
	);
	$cbNewsletter_Debugout->add("removing subscription for " . $db_data["email"], "OK");

      } else {

        $error["database"]["removeSubscription"]["error"] = true;
        $error["database"]["removeSubscription"]["data"] = $result;

        echo $cbNewsletter_HTML->errorbox(sprintf(gettext("Removing subscription for %s failed!"), $db_data["email"]) . "<br>\n" . $result);

        $cbNewsletter_Debugout->add("removing subscription for " . $db_data["email"], "FAILED");

      }

    } else {

      $error["database"]["error"] = true;
      $error["database"]["subscriber_not_exists"] = "This subscription can not be found!";

      echo $cbNewsletter_HTML->errorbox(gettext("Sorry! The link seems to be broken! Please try again - and make sure you have the complete link!<br>\n"));

      $cbNewsletter_Debugout->add("verifying data against the database", "FAILED");

    }

  }




?>
