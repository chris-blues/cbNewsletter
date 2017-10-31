<h3><?php echo gettext("manage subscription"); ?></h3>

<?php

  $Debugout->add("<pre><b>[ enter_subscription.action ]</b>");

// ==============  Honey-pot  ==============

  if (isset($_POST["email"]) and $_POST["email"] != "") {

    $honeypot = true;
    $result = "Spam detected! Aborting all processes!";
    return;

  } else {

    $honeypot = false;
    $result = "OK";

  }

  $Debugout->add("honey pot", $result);

// ==============  Honey-pot  ==============







  include_once(checkout("/lib/verify.transmitted_data.php"));


  if (!$honeypot and !isset($error["verification"]["error"])) {


    if ($query->check_existing($data["address"]) == 0) {

      $Debugout->add("\$query->check_existing(" . $data["address"] . ")", "does not exist in our database");

// =============  create Subscriber object  =============

      $new_subscription = new Subscriber;
      $new_subscription->setData($data["name"], $data["address"]);

      $result = $query->add_subscriber($new_subscription->getdata());

      $new_subscription->setId(
        $query->getSubscribersId(
          $new_subscription->getdata()["hash"]
        )[0]
      );
      $new_subscription->correctTypes();

      $Debugout->add("\$new_subscription:");
      $Debugout->add(print_r($new_subscription, true));

// =============  create Subscriber object  =============





// =============  send verification email  =============

      $optin = new Email("opt_in", $new_subscription, $cbNewsletter["config"]["general"]["locale"]);

      if (!$optin->send_mail()) {

        echo $HTML->errorbox(gettext("Error! Could not send verification mail!\n"));

        $result = "FAILED";

      } else {

        echo $HTML->infobox(gettext("<p>A verification mail has been sent to your inbox. Please click the link, to verify that:</p>\n<ul><li>this mailbox actually belongs to you</li>\n<li>you really want to get our newsletter</li></ul>\n<p>Thanks!</p>\n"));

        $result = "OK";

      }

      $Debugout->add("sending verification mail", $result);

// =============  send verification email  =============



    } else {

      $Debugout->add("\$query->check_existing(" . $data["address"] . ")", "already exists. Aborting...");

      $error["verification"]["database"]["error"] = true;
      $error["verification"]["database"]["data"] = "This email is already subscribed.";

      echo $HTML->infobox(gettext("Sorry! This email is already subscribed. Surely you don't want to receive our newsletters twice!") . " 😉");

    }

  }




// After processing failure show the subscription form again!

  if (isset($error)) {
    include_once(checkout("/views/subscription.form.php"));
  }

  $Debugout->add("</pre>");

?>
