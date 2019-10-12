<?php

  try {

    $NL = cbNewsletter_DIC::get("NLarchive");

  } catch (Exception $e) {

    $error["display_archive"]["exception"] = $e->getMessage();

  }



  if (count($NL) < 1) {

    $error["display_archive"]["data"] = "empty result: newsletter '" . $_GET["id"] . "' does not exist.";

  } else {

    $nl = $NL[0]->getdata();

?>

        <div class="cbNewsletter_info shadow">

          <h3><?php echo $nl["subject"]; ?></h3>

          <p class="notes"><?php echo date("Y-m-d H:i", $nl["date"]); ?></p>

          <div>
            <?php echo nl2br($nl["text"], false); ?>
          </div>

        </div>

        <form method="get" accept-charset="UTF-8">

<?php foreach(assembleGetString("array", array("id" => "")) as $key => $value) { ?>
          <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php } ?>

          <button type="submit"><?php echo gettext("back"); ?></button>
        </form>
        <a href="<?php echo assembleGetString("string", array("id" => "")); ?>"></a>

<?php } ?>
