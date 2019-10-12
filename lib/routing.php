<?php

  $cbNewsletter_Debugout->add("<pre><b>[ routing ]</b>");


// ================  pre display  ================

  if (isset($_POST["job"])) {

    switch($_POST["job"]) {

      case "resend_verification_mail": {

        $subscriber = $cbNewsletter_query->getSubscriberByEmail($_POST["email"]);

        if (count($subscriber) > 0) {

          $db_data = $subscriber[0]->getdata();

          $optout = new Email("opt_in", $db_data, cbNewsletter_DIC::get("locale"));

          if (!$optout->send_mail()) {

            echo $cbNewsletter_HTML->errorbox(gettext("Error! Could not send verification mail!<br>\n"));
            $cbNewsletter_Debugout->add("sending verification mail", "FAILED");

          } else {

            echo $cbNewsletter_HTML->infobox(
              gettext("A verification mail has been sent to your inbox. Please click the link, to verify that you really want to unsubscribe from our newsletters!\n")
            );

            $cbNewsletter_Debugout->add("sending verification mail", "OK");

          }

        }

        break;

      }

    }

  }



// ================  pre display  ================




// =================== Routing ===================


  include_once(cbNewsletter_checkout(
    cbNewsletter_Router::load("/lib/routes.php")
      ->direct(cbNewsletter_DIC::get("view"))
  ));


// =================== Routing ===================




// ===============  post display  ================






// ===============  post display  ================



  $cbNewsletter_Debugout->add("</pre>");

?>
