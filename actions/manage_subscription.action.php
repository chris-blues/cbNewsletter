    <h3><?php echo gettext("Manage subscription"); ?></h3>

<?php

  $debugout .= "<pre><b>[ manage_subscription ]</b>\n";

  $debugout .= str_pad("including /lib/verify.transmitted_data.php", 90);

  $result = include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"));
  if ($result === true) {
    $debugout .= "already done - skipping\n";
  } elseif ($result === false) {
    $debugout .= "FAILED\n";
  }


  $debugout .= str_pad("verifying transmitted data", 90);
  if (isset($data["id"]) and isset($data["hash"])) {

    $debugout .= "passed\n";

// ===============  verify transmitted data against database  ===============

    $userVerification = $query->check_subscription($data["id"], $data["hash"]);

    $debugout .= str_pad("verifying data against the database", 90);

    if ($userVerification == "0") {

      $userVerification = false;
      $error["verification"]["database"] = gettext("No match to this data in the database!");
      $debugout .= "FAILED\n";

    }

    if ($userVerification == "1") {

      $userVerification = true;
      $debugout .= "passed\n";

    }

// ===============  verify transmitted data against database  ===============

  } else {

    $debugout .= "FAILED\n";

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

    $debugout .= "ERROR! Transferred data did not verify!\n";

  }


// =================  data is verified - now let's do some work  =================


  if (isset($userVerification) and $userVerification === true) {

    $subscriber = $query->getSubscriberData($data["id"]);

    $subscriber[0]->correctTypes();

    $db_data = $subscriber[0]->getdata();

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

            $needUpdate["name"] = true;

	  }

	  if (isset($data["new_email"]) and $data["new_email"] != $db_data["email"]) {

            if ($query->check_existing($data["new_email"]) > 0) {

	      $error["change_email"]["error"] = true;
	      $error["change_email"]["data"] = $data["new_email"] . " is already in the database";


            } else {

              $needUpdate["email"] = true;

	    }
	  }

	  if (isset($needUpdate)) {

	    if (isset($needUpdate["email"]) and $needUpdate["email"]) {

	      $result = $query->updateSubscribersEmail($db_data["id"], $data["new_email"]);
	      $debugout .= str_pad("updating email address", 90);

	      if ($result !== false) {

	        echo $HTML->infobox(gettext("Your email address has been updated."));
	        $debugout .= "OK\n";

	      } else {

	        $debugout .= "FAILED\n";

	      }

	      $subscriber = $query->getSubscriberData($data["id"]);
	      $subscriber[0]->correctTypes();
              $db_data = $subscriber[0]->getdata();

              // =============  send verification email  =============

              $optin = new Email("opt_in", $db_data, $cbNewsletter["config"]["general"]["locale"]);

              $debugout .= str_pad("sending verification email to new address", 90);

              if (!$optin->send_mail()) {

                echo $HTML->errorbox(gettext("Error! Could not send verification mail!"));
                $debugout .= "FAILED\n";

              } else {

                echo $HTML->infobox(gettext("<p>A verification mail has been sent to your inbox. Please click the link, to verify that:</p>\n<ul><li>this mailbox actually belongs to you</li>\n<li>you really want to get our newsletter</li></ul>\n<p>Thanks!</p>"));

                $debugout .= "OK\n";

              }

              // =============  send verification email  =============

            }

            if (isset($needUpdate["name"]) and $needUpdate["name"]) {

              $result = $query->updateSubscribersName($db_data["id"], $data["name"]);

              $debugout .= str_pad("updating name", 90);

              if ($result !== false) {

                echo $HTML->infobox(gettext("Your name has been updated."));
                $debugout .= "OK\n";

              } else {

                $debugout .= "FAILED\n";

              }

              $subscriber = $query->getSubscriberData($data["id"]);
              $subscriber[0]->correctTypes();
              $db_data = $subscriber[0]->getdata();

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

            $optout = new Email("opt_out", $db_data, $cbNewsletter["config"]["general"]["locale"]);
            $debugout .= str_pad("sending verification mail", 90);

            if (!$optout->send_mail()) {

              echo $HTML->errorbox(gettext("Error! Could not send verification mail!<br>\n"));
              $debugout .= "FAILED\n";

            } else {

              echo $HTML->infobox(
                gettext("A verification mail has been sent to your inbox. Please click the link, to verify that you really want to unsubscribe from our newsletters!\n")
              );
              $debugout .= "OK\n";

            }
          } else {

            $error["verification"]["agreement"] = "Subscriber did not check the agree-field";
            $debugout .= "Error! I-agree-field not checked! How did you manage that???\n";

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

    $debugout .= str_pad("including /views/manage_subscription.php", 90);
    $debugout .= (include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/manage_subscription.php"))) ? "OK\n" : "FAILED\n";

  } else {

    $debugout .= str_pad("including /views/subscription.form.php", 90);
    $debugout .= (include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/subscription.form.php"))) ? "OK\n" : "FAILED\n";

  }

  $debugout .= "</pre>\n";

?>
