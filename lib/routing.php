<?php

  $Debugout->add("<pre><b>[ routing ]</b>");

// =================== Routing ===================

// ================  pre display  ================

  if (isset($_POST["job"])) {

    switch($_POST["job"]) {

      case "resend_verification_mail": {

        $subscriber = $query->getSubscriberByEmail($_POST["email"]);

        if (count($subscriber) > 0) {

          $db_data = $subscriber[0]->getdata();

          $optout = new Email("opt_in", $db_data, $cbNewsletter["config"]["general"]["locale"]);

          if (!$optout->send_mail()) {

            echo $HTML->errorbox(gettext("Error! Could not send verification mail!<br>\n"));
            $Debugout->add("sending verification mail", "FAILED");

          } else {

            echo $HTML->infobox(
              gettext("A verification mail has been sent to your inbox. Please click the link, to verify that you really want to unsubscribe from our newsletters!\n")
            );

            $Debugout->add("sending verification mail", "OK");

          }

        }

        break;

      }

    }

  }



// ================  pre display  ================





  if (isset($cbNewsletter["config"]["view"]) and strlen($cbNewsletter["config"]["view"]) > 1) {

    include_once(checkout("/actions/" . $_GET["view"] . ".action.php"));

  } else {

    include_once(checkout("/views/subscription.form.php"));

  }





// ===============  post display  ================






// ===============  post display  ================

// =================== Routing ===================

  $Debugout->add("</pre>");

?>
