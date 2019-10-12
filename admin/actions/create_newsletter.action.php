<?php

  $subject = cbNewsletter_findData("subject");
  $text    = cbNewsletter_findData("text");

  if ($subject === false) $subject = "false";
  if ($text    === false) $text    = "false";


  $cbNewsletter_Debugout->add("<pre><b>[ create_newsletter.action ]</b>");
  $cbNewsletter_Debugout->add("cbNewsletter_findData(\"subject\") -> " . $subject);
  $cbNewsletter_Debugout->add("cbNewsletter_findData(\"text\")    -> " . $text);
  $cbNewsletter_Debugout->add("</pre>");


  if ($subject == "false") unset($subject);
  if ($text    == "false") unset($text);

  include_once(cbNewsletter_checkout("/admin/views/create_newsletter.view.php"));

?>
