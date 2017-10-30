<h3><?php echo gettext("manage subscription"); ?></h3>

<?php

  $debugout .= "<pre><b>[ enter_subscription.action ]</b>\n";

// ==============  Honey-pot  ==============

  $debugout .= str_pad("honey pot", 90);

  if (isset($_POST["email"]) and $_POST["email"] != "") {

    $honeypot = true;
    $debugout .= "Spam detected! Aborting all processes!\n";
    return;

  } else {

    $honeypot = false;
    $debugout .= "OK\n";

  }

// ==============  Honey-pot  ==============







  $debugout .= str_pad("including /lib/verify.transmitted_data.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"))) ? :  $debugout .= "FAILED\n";


  if (!$honeypot and !isset($error["verification"]["error"])) {


    if ($query->check_existing($data["address"]) == 0) {

      $debugout .= str_pad("\$query->check_existing(" . $data["address"] . ")", 90) . "does not exist in our database\n";

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

      $debugout .= "\$new_subscription:\n";
      $debugout .= print_r($new_subscription, true) . "\n";

// =============  create Subscriber object  =============





// =============  send verification email  =============

      $optin = new Email("opt_in", $new_subscription, $cbNewsletter["config"]["general"]["locale"]);

      $debugout .= str_pad("sending verification mail", 90);

      if (!$optin->send_mail()) {

        echo $HTML->errorbox(gettext("Error! Could not send verification mail!\n"));

        $debugout .= "FAILED\n";

      } else {

        echo $HTML->infobox(gettext("<p>A verification mail has been sent to your inbox. Please click the link, to verify that:</p>\n<ul><li>this mailbox actually belongs to you</li>\n<li>you really want to get our newsletter</li></ul>\n<p>Thanks!</p>\n"));

        $debugout .= "OK\n";

      }

// =============  send verification email  =============



    } else {

      $debugout .= str_pad("\$query->check_existing(" . $data["address"] . ")", 90) . "already exists. Aborting...\n";

      $error["verification"]["database"]["error"] = true;
      $error["verification"]["database"]["data"] = "This email is already subscribed.";

      echo $HTML->infobox(gettext("Sorry! This email is already subscribed. Surely you don't want to receive our newsletters twice!") . " 😉");

    }

  }




// After processing failure show the subscription form again!

  if (isset($error)) {
    include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/subscription.form.php"));
  }

  $debugout .= "</pre>\n";

?>
