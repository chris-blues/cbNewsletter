<?php

function checkout($filename) {

  global $Debugout, $cbNewsletter;

  $string = "checkout " . $filename;

  $realfilename = realpath($cbNewsletter["config"]["basedir"] . $filename);

  if ($realfilename === false) {

    $Debugout->add($string, "FAILED");

    return false;

  } else {

    $Debugout->add($string, "OK");

    return $realfilename;

  }

}

?>
