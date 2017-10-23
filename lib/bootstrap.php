<?php

  if (isset($_GET["view"])) {
    $cbNewsletter["config"]["view"] = $_GET["view"];
  }

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/HTML.class.php"));
  $HTML = new HTML;

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/functions.php"));

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/initGettext.php"));



  if ($debug) {
    echo "\$cbNewsletter:";
    dump_var($cbNewsletter);
  }



  $cbNewsletter["config"]["database"] = include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/lib/dbcredentials.php"));

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Connection.class.php"));
  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/QueryBuilder.class.php"));

  $connect = Connection::make($cbNewsletter["config"]["database"]);
  if (is_object($connect)) {
    $query = new QueryBuilder($connect);
  }

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Subscriber.class.php"));

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Email.class.php"));




?>
