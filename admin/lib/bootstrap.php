<?php
  date_default_timezone_set('Europe/Berlin');

  // General functions
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/../lib/functions.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/../lib/classes/HTML.class.php"));
  $HTML = new HTML;

  // HTML header
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/header.php"));


  // Database related
  $cbNewsletter["config"]["database"] = include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/dbcredentials.php"));
  $cbNewsletter["config"]["database"]["tablename"] = "cbNewsletter";

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/../lib/classes/Connection.class.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/../lib/classes/QueryBuilder.class.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/QueryBuilderAdmin.class.php"));


  // Other classes
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/../lib/classes/Subscriber.class.php"));



  $connect = Connection::make($cbNewsletter["config"]["database"]);
  if (is_object($connect)) {

    $query = new QueryBuilderAdmin($connect);

  } else {

    $HTML->errorbox(gettext("Error! Could not connect to database!"));
    $error["database"]["connect"] = true;

  }

  $result = $query->init_tables();
  if ($debug) dump_var($result);

?>
