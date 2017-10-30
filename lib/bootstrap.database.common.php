<?php

  $debugout .= "<pre><b>[ bootstrap.database.common ]</b>\n";

  $debugout .= str_pad("loading \$cbNewsletter[\"config\"][\"database\"] from /admin/config/dbcredentials.php: ", 90);
  $cbNewsletter["config"]["database"] = include(realpath($cbNewsletter["config"]["basedir"] . "/admin/config/dbcredentials.php"));
  if (count($cbNewsletter["config"]["database"]) > 0)
       $debugout .= "OK\n";
  else $debugout .= "FAILED\n";


  $debugout .= str_pad("including /lib/classes/Connection.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Connection.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";

  $debugout .= str_pad("including /lib/classes/QueryBuilder.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/QueryBuilder.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";


  $debugout .= "</pre>\n";

?>
