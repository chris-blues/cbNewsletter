<?php

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/Newsletter.class.php"));

  $newsletters = $query->get_verified_subscribers();

  foreach ($newsletters as $key => $newsletter) {

    $Newsletter = new Newsletter($newsletter, $_POST["subject"], $_POST["text"]);

    $Newsletter->send();

  }

?>
