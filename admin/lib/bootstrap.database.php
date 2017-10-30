<?php

  $debugout .= "<pre><b>[ bootstrap.database ]</b>:\n";

  $debugout .= str_pad("including /admin/lib/classes/QueryBuilderAdmin.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/QueryBuilderAdmin.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";


  $debugout .= str_pad("connecting to database: ", 90);
  $connect = Connection::make($cbNewsletter["config"]["database"]);

  if (is_object($connect)) {

    $debugout .= "OK\n";

    $query = new QueryBuilderAdmin($connect);

    $initTables = $query->create_missing_tables();

    $cbNewsletter["config"]["database"]["tables"] = $query->get_table_names();

  } else {

    $debugout .= "FAILED\n";

    echo $HTML->errorbox(gettext("Error! Could not connect to database!"));
    $error["database"]["connect"] = true;

  }


  $debugout .= "</pre>";

?>
