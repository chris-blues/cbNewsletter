<?php

  $Debugout->add("<pre><b>[ bootstrap.database.common ]</b>");


  $cbNewsletter["config"]["database"] = include(checkout("/admin/config/dbcredentials.php", false));
  if (count($cbNewsletter["config"]["database"]) > 0)
       $result = "OK";
  else $result = "FAILED";

  $Debugout->add("loading \$cbNewsletter[\"config\"][\"database\"] from /admin/config/dbcredentials.php: ", $result);


  include_once(checkout("/lib/classes/Connection.class.php"));

  include_once(checkout("/lib/classes/QueryBuilder.class.php"));


  $Debugout->add("</pre>");

?>
