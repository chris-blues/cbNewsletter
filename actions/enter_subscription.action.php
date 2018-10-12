<h3><?php echo gettext("manage subscription"); ?></h3>

<?php

  $cbNewsletter_Debugout->add("<pre><b>[ enter_subscription.action ]</b>");

// ==============  Honey-pot  ==============

  if (isset($_POST["email"]) and $_POST["email"] != "") {

    $honeypot = true;
    $result = "Spam detected! Aborting all processes!";
    return;

  } else {

    $honeypot = false;
    $result = "OK";

  }

  $cbNewsletter_Debugout->add("honey pot", $result);

// ==============  Honey-pot  ==============







  include_once(cbNewsletter_checkout("/lib/verify.transmitted_data.php"));


  if (!$honeypot and !isset($error["verification"]["error"])) {


    if ($cbNewsletter_query->check_existing($data["address"]) == 0) {

      $cbNewsletter_Debugout->add("\$cbNewsletter_query->check_existing(" . $data["address"] . ")", "does not exist in our database");

// =============  create Subscriber object  =============

      $new_subscription = new Subscriber;
      $new_subscription->setData($data["name"], $data["address"]);

      $result = $cbNewsletter_query->add_subscriber($new_subscription->getdata());

      $new_subscription->setId(
        $cbNewsletter_query->getSubscribersId(
          $new_subscription->getdata()["hash"]
        )[0]
      );
      $new_subscription->correctTypes();

      $cbNewsletter_Debugout->add("\$new_subscription:");
      $cbNewsletter_Debugout->add(print_r($new_subscription, true));

// =============  create Subscriber object  =============





// =============  send verification email  =============

      $optin = new Email("opt_in", $new_subscription, cbNewsletter_DIC::get("locale"));

      if (!$optin->send_mail()) {

        echo $cbNewsletter_HTML->errorbox(gettext("Error! Could not send verification mail!\n"));

        $result = "FAILED";

      } else {

        echo $cbNewsletter_HTML->infobox(gettext("<p>A verification mail has been sent to your inbox. Please click the link, to verify that:</p>\n<ul><li>this mailbox actually belongs to you</li>\n<li>you really want to get our newsletter</li></ul>\n<p>Thanks!</p>\n"));

        $result = "OK";

      }

      $cbNewsletter_Debugout->add("sending verification mail", $result);

// =============  send verification email  =============



    } else {

      $cbNewsletter_Debugout->add("\$cbNewsletter_query->check_existing(" . $data["address"] . ")", "already exists.");

      $error["verification"]["database"]["error"] = true;
      $error["verification"]["database"]["data"] = "This email is already subscribed.";

      echo $cbNewsletter_HTML->infobox(gettext("Sorry! This email is already subscribed. Surely you don't want to receive our newsletters twice!"));

      include_once(cbNewsletter_checkout("/views/unsubscribe.view.php"));

    }

  }




// After processing failure show the subscription form again!

  if (isset($error)) {
    include_once(cbNewsletter_checkout("/views/subscription.form.php"));
  }

  $cbNewsletter_Debugout->add("</pre>");

?>
