<?php

  $cbNewsletter_Debugout->add("<pre><b>[ verify.transmitted_data ]</b>");

  include_once(cbNewsletter_checkout("/lib/classes/Data.class.php"));


  $Data = new Data;
  $Data->populate();

  $data = $Data->getdata();

  if (count($data) > 0) $result = "OK";
  else $result = "FAILED";

  $cbNewsletter_Debugout->add("Reading transferred data", $result);

  $cbNewsletter_Debugout->add("extracted dataset " . print_r($data, true));

  $cbNewsletter_Debugout->add("</pre>");



?>
