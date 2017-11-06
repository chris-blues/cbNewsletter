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

  // store this newsletter in the archive

  $Debugout->add(
    "stored this newsletter into `cbNewsletter_archiv`",
    ($query->store_newsletter($_POST["subject"], $_POST["text"])) ? "OK" : "FAILED"
  );

  echo $HTML->infobox($HTML->ol($output));

?>
