<?php

  $debugout .= "<pre><b>[ verify.transmitted_data ]</b>\n";

  $debugout .= str_pad("including /lib/classes/Data.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Data.class.php"))) ? $debugout .= "OK\n" :  $debugout .= "FAILED\n";

  $debugout .= str_pad("Reading transferred data", 90);
  $Data = new Data;
  $Data->populate();

  $data = $Data->getdata();

  if (count($data) > 0) $debugout .= "OK\n";
  else $debugout .= "FAILED\n";

  $debugout .= "extracted dataset " . print_r($data, true);

  $debugout .= "</pre>\n";



?>
