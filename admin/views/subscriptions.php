<?php

  if (isset($_POST["search"])) {
    $search = $_POST["search"];
  } elseif (isset($_GET["search"])) {
    $search = $_GET["search"];
  } else {
    $search = "";
  }

  if (isset($_POST["order"])) {
    $order = $_POST["order"];
  } elseif (isset($_GET["order"])) {
    $order = $_GET["order"];
  } else {
    $order = "email";
  }

  $link = assembleGetString(
    "string", array(
      "view" => "subscriptions",
    )
  );

?>

    <form
      action="index.php<?php echo $link; ?>"
      method="POST"
      accept-charset="utf-8"
      class="center shadow">

      <input
        type="text"
        name="search"
        value="<?php if (strlen($search) > 0) echo $search; ?>">

      <button type="submit"><?php echo gettext("Search"); ?></button>

    </form>



<?php

  $subscribers = $query->get_subscribers($search, $order);

?>

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
        <th><?php echo gettext("Verified"); ?></th>
        <th><?php echo gettext("Delete"); ?></th>
      </tr>

<?php
  foreach ($subscribers as $key => $value) {
    $subscriber = $value->getdata();
    include("views/subscriber.php");
  } ?>

    </table>
