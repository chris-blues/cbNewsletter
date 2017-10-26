    <h3><?php echo gettext("Manage subscription"); ?></h3>

<?php

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"));



  if (isset($data["id"]) and isset($data["hash"])) {

    if ($debug) echo "<b>[ manage_subscription.action ]</b> The data has passed our tests!<br>\n";

// ===============  verify transmitted data against database  ===============

    $userVerification = $query->check_subscription($data["id"], $data["hash"]);

    if ($userVerification == "0") {
      $userVerification = false;
      $error["verification"]["database"] = gettext("No match to this data in the database!");
    }
    if ($userVerification == "1") {
      $userVerification = true;
      if ($debug) echo "<b>[ manage_subscription.action ]</b> The database agrees with this data! Ok, let's go on!<br>\n";
    }

// ===============  verify transmitted data against database  ===============

  }

  if (
    (isset($userVerification) and $userVerification === false)
    or
    !isset($data["id"])
    or
    !isset($data["hash"])
  ) {

    echo $HTML->infobox(
      "<p>" .
      gettext("Sorry! This subscription cannot be found. The following possibilities may help:") . "</p>\n" .
      "<ul>\n<li>" .
      gettext(
        "<b>Your link is broken.</b> Some email-clients add line-breaks to your emails. Make sure to copy the entire link into your browser."
      ) .
      "</li>\n<li>" .
      gettext(
        "<b>You have unsubscribed earlier.</b> This means your subscription has been deleted and is not retrievable. Subscribe again, if you want to receive our newsletters again!"
      ) .
      "</li>\n</ul>"
    );

  }


// =================  data is verified - now let's do some work  =================


  if (isset($userVerification) and $userVerification === true) {

    $subscriber = $query->getSubscriberData($data["id"]);

    if ($debug) {
      echo "\$subscriber:";
      dump_var($subscriber);
    }

    $subscriber[0]->correctTypes();
    $db_data = $subscriber[0]->getdata();


    if ($debug) {
      echo "\$db_data:";
      dump_var($db_data);
    }

  }



  if (
    (isset($userVerification) and $userVerification)
    and
    (isset($db_data["verified"]) and $db_data["verified"] === true)
  ) {

    if (isset($_GET["job"])) {

      switch($_GET["job"]) {

        case "update": {

          if ($data["name"] != $db_data["name"]) {
            if ($debug) echo "<b>[ manage_subscription.action ]</b> Name has changed. Updating database...<br>\n";
            $needUpdate["name"] = true;
	  }

	  if (isset($data["new_email"]) and $data["new_email"] != $db_data["email"]) {
            if ($debug) echo "<b>[ manage_subscription.action ]</b> Email has changed.<br>\n";
            $check_email = $query->check_existing($data["new_email"]);

            if ($check_email > 0) {

	      $error["change_email"]["error"] = true;
	      $error["change_email"]["data"] = $data["new_email"] . " is already in the database (" . $check_email . ")";
	      if ($debug) echo "<b>[ manage_subscription.action ]</b> " . $data["new_email"] . " is already in the database (" . $check_email . ")<br>\n";

            } else {

              $needUpdate["email"] = true;

	    }
	  }

	  if (isset($needUpdate)) {

	    if (isset($needUpdate["email"]) and $needUpdate["email"]) {

	      $result = $query->updateSubscribersEmail($db_data["id"], $data["new_email"]);

	      if ($result !== false) {

	        echo $HTML->infobox(gettext("Your email address has been updated."));

	      }

	      $subscriber = $query->getSubscriberData($data["id"]);
	      $subscriber[0]->correctTypes();
              $db_data = $subscriber[0]->getdata();

              // =============  send verification email  =============

              $optin = new Email("opt_in", $db_data, $cbNewsletter["config"]["locale"]);

              if (!$optin->send_mail()) {

                echo $HTML->errorbox(gettext("Error! Could not send verification mail!"));

              } else {

                echo $HTML->infobox(gettext("<p>A verification mail has been sent to your inbox. Please click the link, to verify that:</p>\n<ul><li>this mailbox actually belongs to you</li>\n<li>you really want to get our newsletter</li></ul>\n<p>Thanks!</p>"));

              }

              // =============  send verification email  =============

	    }

	    if (isset($needUpdate["name"]) and $needUpdate["name"]){

	      $result = $query->updateSubscribersName($db_data["id"], $data["name"]);

	      if ($result !== false) {

	        echo $HTML->infobox(gettext("Your name has been updated."));

	      }

	      $subscriber = $query->getSubscriberData($data["id"]);
	      $subscriber[0]->correctTypes();
              $db_data = $subscriber[0]->getdata();

	    }

            if ($debug) {
              echo "\$db_data:";
              dump_var($db_data);
            }

	  }

          break;
        }

        case "unsubscribe": {

          if (
            (isset($_POST["agree"]) and $_POST["agree"] == "agree")
            or
            (isset($_GET["agree"]) and $_GET["agree"] == "agree")
	  ) {

            $optout = new Email("opt_out", $db_data, $cbNewsletter["config"]["locale"]);

            if (!$optout->send_mail()) {

              echo $HTML->errorbox(gettext("Error! Could not send verification mail!<br>\n"));

            } else {

              echo $HTML->infobox(
                gettext("A verification mail has been sent to your inbox. Please click the link, to verify that you really want to unsubscribe from our newsletters!\n")
	      );

            }
          } else {

            $error["verification"]["agreement"] = "Subscriber did not check the agree-field";

          }

          break;
        }

      }

    }

// =================  data is verified - now let's do some work  =================



    $link = assembleGetString(
              "string",
              array(
                "view" => "manage_subscription",
                "email" => "",
                "job" => "",
                "new_email" => "",
                "id" => "",
                "hash" => "",
              )
    );

    $post = assembleGetString(
              "array",
              array(
                "id" => $db_data["id"],
                "hash" => $db_data["hash"],
                "email" => $db_data["email"],
                "new_email" => "",
                "job" => "",
                "view" => "",
                "standalone" => "",
              )
    );

  }

  if (!isset($db_data["verified"]) or $db_data["verified"] !== true) {

    echo $HTML->infobox(gettext("This email has not been verified yet. Access to this data has been denied."));

  }

  if (
    (isset($userVerification) and $userVerification)
    and
    (isset($db_data["verified"]) and $db_data["verified"] === true)
  ) {

    include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/manage_subscription.php"));

  } else {

    include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/subscription.form.php"));

  }


?>
