<?php

  $subject = findData("subject");
  $text    = findData("text");

  if ($subject === false) $subject = "false";
  if ($text    === false) $text    = "false";


  $debugout .= "<pre><b>[ create_newsletter.action ]</b>\n";
  $debugout .= "findData(\"subject\") -> " . $subject . "\n";
  $debugout .= "findData(\"text\")    -> " . $text . "\n";
  $debugout .= "</pre>\n";


  if ($subject == "false") unset($subject);
  if ($text    == "false") unset($text);

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/admin/views/create_newsletter.view.php"));

?>
