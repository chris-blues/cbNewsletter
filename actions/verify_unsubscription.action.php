<h3><?php echo gettext("verify unsubscription"); ?></h3>

<?php

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"));



// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    if ($debug) echo "<b>[ verify_subscription.action ]</b> Transferred data seems fine so far! Checking against the database!<br>\n";

    $result = $query->check_subscription($data["id"], $data["hash"]);

    if ($debug) {
      echo "<b>[ verify_subscription.action ]</b> $result entry found which matches this data.<br>\n";
    }

    if (intval($result) === 1) {

// ============  verify data against database  ============

      $subscriber = $query->getSubscriberData($data["id"]);
      $subscriber[0]->correctTypes();
      $db_data = $subscriber[0]->getdata();


      if ($debug) {
        echo "\$db_data:";
        dump_var($db_data);
      }

      $result = $query->removeSubscription($data["id"]);

      if ($result) {

        echo $HTML->infobox(
          sprintf(
            gettext("Removing subscription for %s"), $db_data["email"]
	  ) . " : <span class=\"green\">✔</span>"
	);

      } else {

        $error["database"]["removeSubscription"]["error"] = true;
        $error["database"]["removeSubscription"]["data"] = $result;

        echo $HTML->errorbox(sprintf(gettext("Removing subscription for %s failed!"), $db_data["email"]) . "<br>\n" . $result);

      }

    } else {

      $error["database"]["error"] = true;
      $error["database"]["subscriber_not_exists"] = "This subscription can not be found!";

      echo $HTML->errorbox(gettext("Sorry! The link seems to be broken! Please try again - and make sure you have the complete link!<br>\n"));

    }

  }




?>
