<?php

  $cbNewsletter_Debugout->add("<pre><b>[ subscriptions.action ]</b>");

  if (isset($_POST["search"])) {
    $search = $_POST["search"];
  } elseif (isset($_GET["search"])) {
    $search = $_GET["search"];
  } else {
    $search = "";
  }

  if (isset($_POST["order"])) {
    $order = $_POST["order"];
  } elseif (isset($_GET["order"])) {
    $order = $_GET["order"];
  } else {
    $order = "name";
  }

  $link = assembleGetString(
    "string", array(
      "view" => "subscriptions",
    )
  );


  $subscribers = $cbNewsletter_query->get_subscribers($search, $order);
  $cbNewsletter_Debugout->add(
    "getting subscribers",
    count($subscribers) . " subscriber(s) found"
  );

  include_once(cbNewsletter_checkout("/admin/views/subscriptions.view.php"));

  $cbNewsletter_Debugout->add("</pre>");
?>
