<?php

  $cbNewsletter_Debugout->add("<pre><b>[ bootstrap.database.common ]</b>");


  cbNewsletter_DIC::add("database", include(cbNewsletter_checkout("/admin/config/dbcredentials.php", false)));
  if (count(cbNewsletter_DIC::get("database")) > 0)
       $result = "OK";
  else $result = "FAILED";

  $cbNewsletter_Debugout->add("loading cbNewsletter_DIC::[\"database\"] from /admin/config/dbcredentials.php: ", $result);


  include_once(cbNewsletter_checkout("/lib/classes/Connection.class.php"));

  include_once(cbNewsletter_checkout("/lib/classes/QueryBuilder.class.php"));


  $cbNewsletter_Debugout->add("</pre>");

?>
