
      <h2><?php echo gettext("Newsletter archive"); ?></h2>

<?php

  include_once(checkout("/lib/classes/NLarchive.class.php"));

  if (isset($_GET["id"]) and is_numeric($_GET["id"])) {

    DIC::add("NLarchive", $query->show_archive(intval($_GET["id"])));

    include_once(checkout("/views/show_archive.view.php"));

  } else {

    DIC::add("NLarchive", $query->show_archive());

    include_once(checkout("/views/show_archive_list.view.php"));

  }

?>
