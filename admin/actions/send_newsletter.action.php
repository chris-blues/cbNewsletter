<?php

  $Debugout->add("<pre><b>[ send_newsletter.action ]</b>");

  include_once(checkout("/admin/lib/classes/Newsletter.class.php"));

  $newsletters = $query->get_verified_subscribers();

  $Debugout->add("Going to send " . count($newsletters) . " newsletters");

  foreach ($newsletters as $key => $Subscriber) {

    $Newsletter = new Newsletter($Subscriber, $_POST["subject"], $_POST["text"]);

    $sent = $Newsletter->send();
    $subscriberData = $Subscriber->getdata();


    if ($sent === true) {

      $result = "OK";

      $output[] = "Newsletter sent to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;";

    } else {

      $result = "FAILED";

      $output[] = "Error! Could not send newsletter to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;";

    }

    $Debugout->add("Newsletter sent to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;", $result);

  }

  echo $HTML->infobox($HTML->ol($output));

?>
