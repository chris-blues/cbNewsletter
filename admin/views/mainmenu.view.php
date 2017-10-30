
    <nav class="navigation center shadow">


      <form action="index.php" method="GET" accept-charset="utf-8" class="inline">
<?php foreach (assembleGetString("array", array("view" => "subscriptions", "job" => "", "id" => "",)) as $key => $value) { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php } ?>
        <button type="submit"><?php echo gettext("view subscriptions") ?></button>
      </form>


      <form action="index.php" method="GET" accept-charset="utf-8" class="inline">
<?php foreach (assembleGetString("array", array("view" => "create_newsletter", "job" => "", "id" => "",)) as $key => $value) { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php } ?>
        <button type="submit"><?php echo gettext("create newsletter") ?></button>
      </form>


      <form action="index.php" method="GET" accept-charset="utf-8" class="inline">
<?php foreach (assembleGetString("array", array("view" => "config", "job" => "", "id" => "",)) as $key => $value) { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php } ?>
        <button type="submit"><?php echo gettext("settings") ?></button>
      </form>


    </nav>
