<?php

  $cbNewsletter_Debugout->add("<pre><b>[ send_newsletter.action ]</b>");

//   include_once(cbNewsletter_checkout("/admin/lib/classes/Newsletter.class.php"));
  include_once(cbNewsletter_checkout("/admin/lib/classes/ProcessorFormData.class.php"));

  $newsletters = $cbNewsletter_query->get_verified_subscribers();

  $cbNewsletter_Debugout->add("Going to send " . count($newsletters) . " newsletters");

  foreach ($newsletters as $key => $Subscriber) {

//     $Newsletter = new Newsletter($Subscriber, $_POST["subject"], $_POST["text"]);
    $Newsletter = new Processor_formdata($Subscriber->getdata());

    $sent = $Newsletter->send();
    $subscriberData = $Subscriber->getdata();


    if ($sent === true) {

      $result = "OK";

      $output[] = "Newsletter sent to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;";

    } else {

      $result = "FAILED";

      $output[] = "Error! Could not send newsletter to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;";

    }

    $cbNewsletter_Debugout->add("Newsletter sent to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;", $result);

  }

  // store this newsletter in the archive

  $cbNewsletter_Debugout->add(
    "stored this newsletter into `cbNewsletter_archive`",
    ($cbNewsletter_query->store_newsletter($_POST["subject"], $_POST["text"])) ? "OK" : "FAILED"
  );

  echo $cbNewsletter_HTML->infobox($cbNewsletter_HTML->ol($output));

?>
