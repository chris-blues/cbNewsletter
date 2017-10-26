<?php

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/Newsletter.class.php"));

  $newsletters = $query->get_verified_subscribers();

  foreach ($newsletters as $key => $Subscriber) {

    $Newsletter = new Newsletter($Subscriber, $_POST["subject"], $_POST["text"]);

    $sent = $Newsletter->send();
    $subscriberData = $Subscriber->getdata();

    if ($sent) {

      $HTML->infobox("Newsletter sent to " . $subscriberData["name"] . " <" . $subscriberData["email"] . ">");

    } else {

      $HTML->errorbox("Error! Could not send newsletter to " . $subscriberData["name"] . " <" . $subscriberData["email"] . ">");

    }

  }

?>
