
      <h2><?php echo gettext("Newsletter archive"); ?></h2>

<?php

  include_once(cbNewsletter_checkout("/lib/classes/NLarchive.class.php"));

  if (isset($_GET["id"]) and is_numeric($_GET["id"])) {

    cbNewsletter_DIC::add("NLarchive", $cbNewsletter_query->show_archive(intval($_GET["id"])));

    include_once(cbNewsletter_checkout("/views/show_archive.view.php"));

  } else {

    cbNewsletter_DIC::add("NLarchive", $cbNewsletter_query->show_archive());

    include_once(cbNewsletter_checkout("/views/show_archive_list.view.php"));

  }

?>
