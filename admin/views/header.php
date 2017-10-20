<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="robots" content="noindex,nofollow">

    <link rel="stylesheet" href="../style.css" type="text/css">

    <title><?php echo $_SERVER["SERVER_NAME"]; ?> - cbNewsletter - admin-area</title>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="page-topic" content="<?php echo $_SERVER["SERVER_NAME"]; ?> - cbNewsletter - admin-area">
    <meta name="description" content="<?php echo $_SERVER["SERVER_NAME"]; ?> - cbNewsletter - admin-area">

  </head>

  <body>

    <h1 class="center"><?php echo gettext("Newsletter") ?></h1>

    <div class="navigation center shadow">
      <a href="<?php echo assembleGetString("string", array("view" => "subscriptions")); ?>"><?php echo gettext("subscriptions") ?></a>
      •
      <a href="<?php echo assembleGetString("string", array("view" => "create_newsletter")); ?>"><?php echo gettext("send newsletter") ?></a>
    </div>
