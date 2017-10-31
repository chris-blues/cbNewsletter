<?php

  $Debugout->add("<pre><b>[ subscriptions.action ]</b>");

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


  $subscribers = $query->get_subscribers($search, $order);
  $Debugout->add(
    "getting subscribers",
    count($subscribers) . " subscriber(s) found"
  );

  include_once(checkout("/admin/views/subscriptions.view.php"));

  $Debugout->add("</pre>");
?>
