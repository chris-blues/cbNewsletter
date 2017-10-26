
    <div id="preview" class="shadow">
      <h3 id="preview_subject"></h3>
      <pre id="preview_text"></pre>
    </div>


    <form action="index.php<?php echo assembleGetString("string", array("view" => "send_newsletter")) ?>" method="POST" accept-charset="utf-8" class="center shadow">
      <label><?php echo gettext("subject"); ?><br>
        <input
          type="text"
          name="subject"
          value="<?php if (isset($subject)) echo $subject; ?>"
          placeholder="<?php echo gettext("subject"); ?>"
          class="createNewsletter"
          id="subject">
      </label><br>
      <label><?php echo gettext("newsletter text"); ?><br>
        <textarea
          type="text"
          name="text"
          placeholder="<?php echo gettext("newsletter text"); ?>"
          class="createNewsletter"
          id="text"><?php if (isset($text)) echo $text; ?></textarea>
      </label><br>
      <button type="submit"><?php echo gettext("send newsletter"); ?></button>
      <button type="button" id="button_preview"><?php echo gettext("preview"); ?></button>
      <button type="reset"><?php echo gettext("reset"); ?></button>
    </form>

