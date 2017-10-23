<?php
  date_default_timezone_set('Europe/Berlin');

  // General functions
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/functions.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/HTML.class.php"));
  $HTML = new HTML;

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/initGettext.php"));

  // HTML header
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/header.php"));


  // Database related
  $cbNewsletter["config"]["database"] = include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/dbcredentials.php"));

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Connection.class.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/QueryBuilder.class.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/QueryBuilderAdmin.class.php"));


  // Other classes
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Subscriber.class.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/classes/Maintenance.class.php"));



  $connect = Connection::make($cbNewsletter["config"]["database"]);
  if (is_object($connect)) {

    $query = new QueryBuilderAdmin($connect);

    $initTables = $query->create_missing_tables();

    $cbNewsletter["config"]["database"]["tables"] = $query->get_table_names();

  } else {

    echo $HTML->errorbox(gettext("Error! Could not connect to database!"));
    $error["database"]["connect"] = true;

  }


  $tables_maintenance = $query->get_maintenance_data();

  $maintenance_info = "";
  foreach ($tables_maintenance as $key => $table) {

    $maintenance_info .= $table->get_last_optimization();

    if ($table->needs_maintenance()) $query->optimize_table($table->get_name());

  }

  if ($debug) echo $HTML->infobox($maintenance_info, "debug");

  if (isset($_POST["job"]) and $_POST["job"] == "optimize_tables") {

    foreach ($cbNewsletter["config"]["database"]["tables"] as $name) {
      $result = $query->optimize_table($name);
    }

  }

?>
