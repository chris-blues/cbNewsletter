
    <div class="center shadow">

      <form
        action="index.php<?php echo $link; ?>"
        method="POST"
        accept-charset="utf-8"
        class="inline">

        <input
          type="text"
          name="search"
          value="<?php if (strlen($search) > 0) echo $search; ?>">

        <button type="submit"><?php echo gettext("Search"); ?></button>

      </form>

      <form
        action="index.php<?php echo assembleGetString("string", array("view" => "subscriptions", "order" => $order)); ?>"
        method="POST"
        accept-charset="utf-8"
        class="inline">

        <button type="submit"><?php echo gettext("Clear search"); ?></button>

      </form>

    </div>



    <table id="subscribers">

      <tr>
        <th>
            <a
                href="index.php<?php echo assembleGetString("string", array("view" => "subscriptions", "order" => "id", "search" => $search)); ?>"
                class="<?php if ($order == "id") echo "active"; ?>">
                <?php echo gettext("ID"); ?>
            </a>
        </th>
        <th>
            <a
                href="index.php<?php echo assembleGetString("string", array("view" => "subscriptions", "order" => "name", "search" => $search)); ?>"
                class="<?php if ($order == "name") echo "active"; ?>">
                <?php echo gettext("Name"); ?>
            </a>
        </th>
        <th>
            <a
                href="index.php<?php echo assembleGetString("string", array("view" => "subscriptions", "order" => "email", "search" => $search)); ?>"
                class="<?php if ($order == "email") echo "active"; ?>">
                <?php echo gettext("Email"); ?>
            </a>
        </th>
        <th>
            <a
                href="index.php<?php echo assembleGetString("string", array("view" => "subscriptions", "order" => "subscribed", "search" => $search)); ?>"
                class="<?php if ($order == "subscribed") echo "active"; ?>">
                <?php echo gettext("Subscribed"); ?>
            </a>
        </th>
        <th><?php echo gettext("Verified"); ?></th>
        <th><?php echo gettext("Delete"); ?></th>
      </tr>

<?php
  foreach ($subscribers as $key => $value) {
    $subscriber = $value->getdata();
    include(realpath(cbNewsletter_DIC::get("basedir") . "/admin/views/subscriber.php"));
  } ?>

    </table>
