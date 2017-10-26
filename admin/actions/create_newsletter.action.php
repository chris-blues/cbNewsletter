<?php

  $subject = findData("subject");
  if ($subject === false) unset($subject);

  $text = findData("text");
  if ($text === false) unset($text);

  if ($debug) {

    $debugMessage = "<pre>findData(\"subject\") -> " . $subject . " (" . gettype($subject) . "(" . strlen($subject) . "))\n";
    $debugMessage .= "findData(\"text\") -> " . $text . " (" . gettype($text) . "(" . strlen($text) . "))</pre>\n";

    echo $HTML->infobox($debugMessage, "debug");

  }

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/create_newsletter.view.php"));

?>
