<?php


function checkout($filename, $exit = true) {

  global $Debugout, $debug, $cbNewsletter, $HTML;

  if (strncmp($filename, "/", 1) != 0) $filename = "/" . $filename;

  $string = "checkout " . $filename;

  $realfilename = realpath($cbNewsletter["basedir"] . $filename);

  if ($realfilename === false) {

    $Debugout->add($string, "<b>FAILED</b>");

    if ($exit === true) {

      $Debugout->add($filename . " not found. Exit...");

      echo $HTML->errorbox(
        "<h1>" . gettext("Fatal error") . "</h1>\n<p>" . sprintf(gettext("%s not found. Exit..."), $filename) . "</p>\n" .
        "<h2>" . gettext("Debug output") . "</h2>\n" . $Debugout->output()
      );

      exit;

    }

  } else {

    $Debugout->add($string, "OK");

    return $realfilename;

  }

}

?>
