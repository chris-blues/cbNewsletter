<?php

  $debugout .= "<pre><b>[ subscriptions.action ]</b>\n";

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


  $debugout .= str_pad("getting subscribers", 90);
  $subscribers = $query->get_subscribers($search, $order);
  $debugout .= count($subscribers) . " subscriber(s) found\n";

  $debugout .= str_pad("including /admin/views/subscriptions.view.php", 90);
  $debugout .= (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/subscriptions.view.php"))) ? "OK\n" : "FAILED\n";

  $debugout .= "</pre>\n";
?>
