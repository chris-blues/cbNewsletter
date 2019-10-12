<?php

  $cbNewsletter_Debugout->add("<pre><b>[ create_newsletter.view ]</b>");

?>

    <div class="shadow" id="preview_wrapper">
      <h2><?php echo gettext("preview"); ?></h2>
      <div class="shadow cbNewsletter_info" id="preview">
        <h3 id="preview_subject"></h3>
        <div id="preview_text"></div>
      </div>
      <button id="hide_preview" type="button>"><?php echo gettext("hide preview"); ?></button>
    </div>

<?php

  if (!isset($subject)) $subject = $_SERVER["SERVER_NAME"] . " " . gettext("Newsletter");
  if (!isset($text))    $text = gettext("Hello %name%,\n\nthis is a newsletter from %server% .\n\nBest regards,\nyour newsletter team\n\nYou will find an unsubscription link below:\n");

?>

    <div id="boxes_wrapper">
      <form action="index.php<?php echo assembleGetString("string", array("view" => "send_newsletter", "job" => "", "id" => "")) ?>" method="POST" accept-charset="utf-8" class="shadow flex_left" id="create_newsletter">

        <h2><?php echo gettext("create newsletter"); ?></h2>

        <div class="ten">

          <label for="subject"><b><?php echo gettext("subject"); ?></b></label><br>
          <input
            type="text"
            name="subject"
            value="<?php if (isset($subject)) echo $subject; ?>"
            placeholder="<?php echo gettext("subject"); ?>"
            class="createNewsletter"
            id="subject"><br>

          <label for="text"><b><?php echo gettext("newsletter text"); ?></b></label>
        </div>

        <div id="form_wrapper">

          <div class="flex one">

            <div
              type="text"
              name="text"
              placeholder="<?php echo gettext("newsletter text"); ?>"
              class="createNewsletter"
              id="text"><?php if (isset($text)) echo $text; ?></div>

          </div>

          <div class="flex two">

            <button type="reset" class="full">
              <img src="reset.png"><br>
              <?php echo gettext("reset"); ?>
            </button><br>
            <button type="button" id="button_save_template" class="full">
              <img src="save.png"><br>
              <?php echo gettext("save template"); ?>
            </button><br>

            <div id="save_template" class="hidden">

              <label for="template_name"><?php echo gettext("template name"); ?></label><br>
              <input type="text" name="name" value="" id="input_template_name"><br>

              <button type="button" id="hide_save_template"><?php echo gettext("cancel"); ?></button>
              <button type="button" id="button_save_template_action"><?php echo gettext("OK"); ?></button>

            </div>

            <button type="button" id="button_preview" class="full">
              <img src="preview.png"><br>
              <?php echo gettext("preview"); ?>
            </button><br>
            <button type="submit" class="full">
              <img src="send.png"><br>
              <?php echo gettext("send newsletter"); ?>
            </button>
          </div>

        </div>

      </form>

      <form id="form_save_template" action="<?php echo assembleGetString("string", array("job"=>"save_template", "id" => "")); ?>" accept-charset="utf-8" method="post">

        <input type="hidden" name="name" value="" id="template_name">
        <input type="hidden" name="subject" value="" id="template_subject">
        <input type="hidden" name="text" value="" id="template_text">

      </form>

      <div class="shadow flex_left" id="newsletter_templates">

        <h2><?php echo gettext("templates"); ?></h2>

        <ul id="template_files">

<?php

      $newsletter_templates = $cbNewsletter_query->get_templates();
      $cbNewsletter_Debugout->add("getting templates", count($newsletter_templates) . " found");

      foreach ($newsletter_templates as $key => $value) {

        $Template = $value->getdata();

        // normalize line-breaks to "\n"
        $Template["text"] = str_replace(array("\r\n", "\r"), "\n", $Template["text"]);

?>
          <li
            id="template_<?php echo $Template["name"]; ?>"
            class="template_files"
            data-subject="<?php echo $Template["subject"]; ?>"
            data-text="<?php echo $Template["text"]; ?>">

            <a href="##">
              <?php echo $Template["name"]; ?>
            </a>
            <a class="right" href="<?php echo assembleGetString("string", array("job"=>"delete_template", "id"=>$Template["id"])); ?>">
              <img src="delete.png" class="button_delete_template" title="<?php echo gettext("delete template"); ?>" alt="<?php echo gettext("delete template"); ?>">
            </a>
          </li>
<?php

      }

?>

        </ul>

      </div>

    </div>

<?php $cbNewsletter_Debugout->add("</pre>"); ?>
