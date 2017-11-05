<?php

  $Debugout->add("<pre><b>[ bootstrap.database ]</b>");


  $connect = Connection::make(DIC::get("database"));

  if (is_object($connect)) {

    $Debugout->add("connecting to database", "OK");

    $query = new QueryBuilder($connect);


    $tmp = DIC::get("database");
    $tmp["tables"] = $query->get_table_names();
    DIC::add("database", $tmp);
    unset($tmp);

  } else {

    $Debugout->add("connecting to database", "FAILED");

    echo $HTML->errorbox(gettext("Error! Could not connect to database!"));
    $error["database"]["connect"] = true;

  }



  $Debugout->add("</pre>");

?>
