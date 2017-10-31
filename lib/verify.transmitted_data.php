<?php

  $Debugout->add("<pre><b>[ verify.transmitted_data ]</b>");

  include_once(checkout("/lib/classes/Data.class.php"));


  $Data = new Data;
  $Data->populate();

  $data = $Data->getdata();

  if (count($data) > 0) $result = "OK";
  else $result = "FAILED";

  $Debugout->add("Reading transferred data", $result);

  $Debugout->add("extracted dataset " . print_r($data, true));

  $Debugout->add("</pre>");



?>
