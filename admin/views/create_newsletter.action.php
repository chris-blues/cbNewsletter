<?php

  $subject = findData("subject");
  if ($subject === false) unset($subject);

  $text = findData("text");
  if ($text === false) unset($text);

  echo $HTML->infobox("findData(\"subject\") -> " . $subject . " (" . gettype($subject) . "(" . strlen($subject) . "))", "debug");

?>


    <form action="index.php<?php echo assembleGetString("string", array("view" => "send_newsletter")) ?>" method="POST" accept-charset="utf-8" class="center shadow">
      <label><?php echo gettext("subject"); ?><br>
        <input type="text" name="subject" value="<?php if (isset($subject)) echo $subject; ?>" placeholder="<?php echo gettext("subject"); ?>" class="createNewsletter">
      </label><br>
      <label><?php echo gettext("newsletter text"); ?><br>
        <textarea type="text" name="text" placeholder="<?php echo gettext("newsletter text"); ?>" class="createNewsletter"><?php if (isset($text)) echo $text; ?></textarea>
      </label><br>
      <button type="submit"><?php echo gettext("send newsletter"); ?></button>
      <button type="button"><?php echo gettext("preview"); ?></button>
      <button type="reset"><?php echo gettext("reset"); ?></button>
    </form>

