    <h3><?php echo gettext("Manage subscription"); ?></h3>

<?php

  $cbNewsletter_Debugout->add("<pre><b>[ manage_subscription ]</b>");


  $result = include_once(cbNewsletter_checkout("/lib/verify.transmitted_data.php"));
  if ($result === true) {
    $cbNewsletter_Debugout->add("including /lib/verify.transmitted_data.php", "already done - skipping");
  } elseif ($result === false) {
    $cbNewsletter_Debugout->add("including /lib/verify.transmitted_data.php", "FAILED");
  }



  if (isset($data["id"]) and isset($data["hash"])) {

    $cbNewsletter_Debugout->add("verifying transmitted data", "passed");

// ===============  verify transmitted data against database  ===============

    $userVerification = $cbNewsletter_query->check_subscription($data["id"], $data["hash"]);

    if ($userVerification == "0") {

      $userVerification = false;
      $error["verification"]["database"] = gettext("No match to this data in the database!");
      $cbNewsletter_Debugout->add("verifying data against the database", "FAILED");

    }

    if ($userVerification == "1") {

      $userVerification = true;
      $cbNewsletter_Debugout->add("verifying data against the database", "passed");

    }

// ===============  verify transmitted data against database  ===============

  } else {

    $cbNewsletter_Debugout->add("verifying transmitted data", "FAILED");

  }



  if (
    (isset($userVerification) and $userVerification === false)
    or
    !isset($data["id"])
    or
    !isset($data["hash"])
  ) {

    echo $cbNewsletter_HTML->infobox(
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

    $cbNewsletter_Debugout->add("ERROR! Transferred data did not verify!");

  }


// =================  data is verified - now let's do some work  =================


  if (isset($userVerification) and $userVerification === true) {

    $subscriber = $cbNewsletter_query->getSubscriberData($data["id"]);

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

            if ($cbNewsletter_query->check_existing($data["new_email"]) > 0) {

	      $error["change_email"]["error"] = true;
	      $error["change_email"]["data"] = $data["new_email"] . " is already in the database";


            } else {

              $needUpdate["email"] = true;

	    }
	  }

	  if (isset($needUpdate)) {

	    if (isset($needUpdate["email"]) and $needUpdate["email"]) {

	      $result = $cbNewsletter_query->updateSubscribersEmail($db_data["id"], $data["new_email"]);

	      if ($result !== false) {

	        echo $cbNewsletter_HTML->infobox(gettext("Your email address has been updated."));
	        $cbNewsletter_Debugout->add("updating email address", "OK");

	      } else {

	        $cbNewsletter_Debugout->add("updating email address", "FAILED");

	      }

	      $subscriber = $cbNewsletter_query->getSubscriberData($data["id"]);
	      $subscriber[0]->correctTypes();
              $db_data = $subscriber[0]->getdata();

              // =============  send verification email  =============

              $optin = new Email("opt_in", $db_data, cbNewsletter_DIC::get("locale"));

              if (!$optin->send_mail()) {

                echo $cbNewsletter_HTML->errorbox(gettext("Error! Could not send verification mail!"));
                $cbNewsletter_Debugout->add("sending verification email to new address", "FAILED");

              } else {

                echo $cbNewsletter_HTML->infobox(gettext("<p>A verification mail has been sent to your inbox. Please click the link, to verify that:</p>\n<ul><li>this mailbox actually belongs to you</li>\n<li>you really want to get our newsletter</li></ul>\n<p>Thanks!</p>"));

                $cbNewsletter_Debugout->add("sending verification email to new address", "OK");

              }

              // =============  send verification email  =============

            }

            if (isset($needUpdate["name"]) and $needUpdate["name"]) {

              $result = $cbNewsletter_query->updateSubscribersName($db_data["id"], $data["name"]);

              if ($result !== false) {

                echo $cbNewsletter_HTML->infobox(gettext("Your name has been updated."));
                $cbNewsletter_Debugout->add("updating name", "OK");

              } else {

                $cbNewsletter_Debugout->add("updating name", "FAILED");

              }

              $subscriber = $cbNewsletter_query->getSubscriberData($data["id"]);
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

            $optout = new Email("opt_out", $db_data, cbNewsletter_DIC::get("locale"));

            if (!$optout->send_mail()) {

              echo $cbNewsletter_HTML->errorbox(gettext("Error! Could not send verification mail!<br>\n"));
              $cbNewsletter_Debugout->add("sending verification mail", "FAILED");

            } else {

              echo $cbNewsletter_HTML->infobox(
                gettext("A verification mail has been sent to your inbox. Please click the link, to verify that you really want to unsubscribe from our newsletters!\n")
              );
              $cbNewsletter_Debugout->add("sending verification mail", "OK");

            }

          } else {

            $error["verification"]["agreement"] = "Subscriber did not check the agree-field";
            $cbNewsletter_Debugout->add("Error! I-agree-field not checked! How did you manage that???");

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

    echo $cbNewsletter_HTML->infobox(gettext("This email has not been verified yet. Access to this data has been denied."));

  }

  if (
    (isset($userVerification) and $userVerification)
    and
    (isset($db_data["verified"]) and $db_data["verified"] === true)
  ) {

    include_once(cbNewsletter_checkout("/views/manage_subscription.php"));

  } else {

    include_once(cbNewsletter_checkout("/views/subscription.form.php"));

  }

  $cbNewsletter_Debugout->add("</pre>");

?>
