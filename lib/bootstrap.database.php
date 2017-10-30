<?php

  $debugout .= "<pre><b>[ bootstrap.database ]</b>\n";



  $debugout .= str_pad("connecting to database: ", 90);
  $connect = Connection::make($cbNewsletter["config"]["database"]);

  if (is_object($connect)) {

    $debugout .= "OK\n";

    $query = new QueryBuilder($connect);

    $cbNewsletter["config"]["database"]["tables"] = $query->get_table_names();

  } else {

    $debugout .= "FAILED\n";

    echo $HTML->errorbox(gettext("Error! Could not connect to database!"));
    $error["database"]["connect"] = true;

  }



  $debugout .= "</pre>";

?>
