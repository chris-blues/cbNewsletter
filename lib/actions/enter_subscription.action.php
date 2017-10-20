<h3><?php echo gettext("manage subscription"); ?></h3>

<?php

// ==============  Honey-pot  ==============

  if (isset($_POST["email"]) and $_POST["email"] != "") {

    $honeypot = true;

  } else {

    $honeypot = false;

  }

// ==============  Honey-pot  ==============







  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"));


  if (!$honeypot and !isset($error["verification"]["error"])) {


    if ($query->check_existing($data["address"]) == 0) {


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

      if ($debug) {
        echo "\$new_subscription:";
        dump_var($new_subscription);
      }

// =============  create Subscriber object  =============





// =============  send verification email  =============

      $optin = new Email("opt_in", $new_subscription);

      if (!$optin->send_mail()) {

        $HTML->errorbox(gettext("Error! Could not send verification mail!\n"));

      } else {

        $HTML->infobox(gettext("<p>A verification mail has been sent to your inbox. Please click the link, to verify that:</p>\n<ul><li>this mailbox actually belongs to you</li>\n<li>you really want to get our newsletter</li></ul>\n<p>Thanks!</p>\n"));
      }

// =============  send verification email  =============

    } else {

      $error["verification"]["database"]["error"] = true;
      $error["verification"]["database"]["data"] = "This email is already subscribed.";

      $HTML->infobox(gettext("Sorry! This email is already subscribed. Surely you don't want to receive our newsletters twice!") . " 😉");

    }

  }




// After processing show the subscription form again!

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/subscription.form.php"));


?>
