<?php

  $cbNewsletter_Debugout->add("<pre><b>[ bootstrap.database ]</b>");

  include_once(cbNewsletter_checkout("/admin/lib/classes/QueryBuilderAdmin.class.php"));


  $connect = cbNewsletter_Connection::make(cbNewsletter_DIC::get("database"));

  if (is_object($connect)) {

    $cbNewsletter_Debugout->add("connecting to database", "OK");

    $cbNewsletter_query = new cbNewsletter_QueryBuilderAdmin($connect);

    $initTables = $cbNewsletter_query->create_missing_tables();


    $tmp = cbNewsletter_DIC::get("database");
    $tmp["tables"] = $cbNewsletter_query->get_table_names();
    cbNewsletter_DIC::add("database", $tmp);
    unset($tmp);

  } else {

    $cbNewsletter_Debugout->add("connecting to database", "FAILED");

    echo $cbNewsletter_HTML->errorbox(gettext("Error! Could not connect to database!"));
    $error["database"]["connect"] = true;

  }




  $cbNewsletter_Debugout->add("</pre>");

?>
