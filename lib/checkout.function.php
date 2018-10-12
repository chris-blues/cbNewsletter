<?php


function cbNewsletter_checkout($filename, $exit = true) {

  global $cbNewsletter_Debugout, $debug, $cbNewsletter_HTML;

  if (strncmp($filename, "/", 1) != 0) $filename = "/" . $filename;

  $string = "checkout " . $filename;

  $realfilename = realpath(cbNewsletter_DIC::get("basedir") . $filename);

  if ($realfilename === false) {

    $cbNewsletter_Debugout->add($string, "<b>FAILED</b>");

    if ($exit === true) {

      $cbNewsletter_Debugout->add($filename . " not found. Exit...");

      echo $cbNewsletter_HTML->errorbox(
        "<h1>" . gettext("Fatal error") . "</h1>\n<p>" . sprintf(gettext("%s not found. Exit..."), $filename) . "</p>\n" .
        "<h2>" . gettext("Debug output") . "</h2>\n" . $cbNewsletter_Debugout->output()
      );

      exit;

    }

  } else {

    $cbNewsletter_Debugout->add($string, "OK");

    return $realfilename;

  }

}

?>
