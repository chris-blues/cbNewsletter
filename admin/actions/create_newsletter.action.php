<?php

  $subject = findData("subject");
  $text    = findData("text");

  if ($subject === false) $subject = "false";
  if ($text    === false) $text    = "false";


  $Debugout->add("<pre><b>[ create_newsletter.action ]</b>");
  $Debugout->add("findData(\"subject\") -> " . $subject);
  $Debugout->add("findData(\"text\")    -> " . $text);
  $Debugout->add("</pre>");


  if ($subject == "false") unset($subject);
  if ($text    == "false") unset($text);

  include_once(checkout("/admin/views/create_newsletter.view.php"));

?>
