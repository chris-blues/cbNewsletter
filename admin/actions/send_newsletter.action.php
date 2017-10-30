<?php

  $debugout .="<pre><b>[ send_newsletter.action ]</b>:\n";

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/Newsletter.class.php"));

  $newsletters = $query->get_verified_subscribers();

  $debugout .= "Going to send " . count($newsletters) . " newsletters\n";

  foreach ($newsletters as $key => $Subscriber) {

    $Newsletter = new Newsletter($Subscriber, $_POST["subject"], $_POST["text"]);

    $sent = $Newsletter->send();
    $subscriberData = $Subscriber->getdata();


    $debugout .= str_pad("Newsletter sent to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;", 90);

    if ($sent === true) {

      $debugout .= "OK\n";

      $output[] = "Newsletter sent to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;";

    } else {

      $debugout .= "FAILED\n";

      $output[] = "Error! Could not send newsletter to " . $subscriberData["name"] . " &lt;" . $subscriberData["email"] . "&gt;";

    }

  }

  echo $HTML->infobox($HTML->ol($output));

?>
