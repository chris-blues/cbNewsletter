<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="robots" content="noindex,nofollow">

    <link rel="stylesheet" href="../style.css" type="text/css">
    <script type="text/javascript" src="scripts.js"></script>

    <title><?php echo $_SERVER["SERVER_NAME"]; ?> - cbNewsletter - admin-area</title>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="page-topic" content="<?php echo $_SERVER["SERVER_NAME"]; ?> - cbNewsletter - admin-area">
    <meta name="description" content="<?php echo $_SERVER["SERVER_NAME"]; ?> - cbNewsletter - admin-area">

  </head>

  <body>

    <h1 class="center"><?php echo gettext("Newsletter") ?></h1>

    <nav class="navigation center shadow">


      <form action="index.php" method="GET" accept-charset="utf-8" class="inline">
<?php foreach (assembleGetString("array", array("view" => "subscriptions")) as $key => $value) { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php } ?>
        <button type="submit"><?php echo gettext("view subscriptions") ?></button>
      </form>


      <form action="index.php" method="GET" accept-charset="utf-8" class="inline">
<?php foreach (assembleGetString("array", array("view" => "create_newsletter")) as $key => $value) { ?>
        <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php } ?>
        <button type="submit"><?php echo gettext("create newsletter") ?></button>
      </form>


    </nav>

    <div class="jobs shadow">

      <h2 class="inline"><?php echo gettext("Maintenance tasks") ?></h2> &nbsp;

      <form action="index.php<?php echo assembleGetString("string"); ?>" method="POST" accept-charset="utf-8" class="inline">
        <input type="hidden" name="job" value="optimize_tables">
        <button type="submit"><?php echo gettext("optimize database tables"); ?></button>
      </form>

    </div>
