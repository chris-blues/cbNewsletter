<?php

  $Debugout->add("<pre><b>[ bootstrap.database ]</b>");

  include_once(checkout("/admin/lib/classes/QueryBuilderAdmin.class.php"));


  $connect = Connection::make($cbNewsletter["config"]["database"]);

  if (is_object($connect)) {

    $Debugout->add("connecting to database", "OK");

    $query = new QueryBuilderAdmin($connect);

    $initTables = $query->create_missing_tables();

    $cbNewsletter["config"]["database"]["tables"] = $query->get_table_names();

  } else {

    $Debugout->add("connecting to database", "FAILED");

    echo $HTML->errorbox(gettext("Error! Could not connect to database!"));
    $error["database"]["connect"] = true;

  }




  $Debugout->add("</pre>");

?>
