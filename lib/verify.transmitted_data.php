<?php

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Data.class.php"));

  $Data = new Data;
  $Data->populate();
  $data = $Data->getdata();


  if ($debug) {

    echo "\$_GET:";
    dump_var($_GET);

    echo "\$_POST:";
    dump_var($_POST);

    echo "\$data:";
    dump_var($data);

  }


?>
