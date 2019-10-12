<?php

  $cbNewsletter_Debugout->add("<pre><b>[ bootstrap.common ]</b>");

  date_default_timezone_set('Europe/Berlin');
  $cbNewsletter_Debugout->add(
    "date_default_timezone set to:",
    date_default_timezone_get()
  );




  include_once(cbNewsletter_checkout("/lib/classes/Router.class.php"));

  include_once(cbNewsletter_checkout("/lib/classes/Request.class.php"));

  cbNewsletter_DIC::add("view", cbNewsletter_Request::view());

  $cbNewsletter_Debugout->add("cbNewsletter_DIC::[\"view\"] set to", cbNewsletter_DIC::get("view"));



  // General functions
  include_once(cbNewsletter_checkout("/lib/classes/HTML.class.php"));
  $cbNewsletter_HTML = new cbNewsletter_HTML;

  include_once(cbNewsletter_checkout("/lib/functions.php"));


  // Other classes
  include_once(cbNewsletter_checkout("/lib/classes/Subscriber.class.php"));


  // gettext
  include_once(cbNewsletter_checkout("/lib/initGettext.php"));

  if (
    !is_file(cbNewsletter_DIC::get("basedir")."/admin/config/dbcredentials.php")
    and
    strncmp($_SERVER["PHP_SELF"], "/admin/", strlen("/admin/")) == 0
  ) {
    $_GET["view"] = "config";
  }


  if (!isset($_GET["view"]) or $_GET["view"] != "config") {

    // Database related
    include_once(cbNewsletter_checkout("/lib/bootstrap.database.common.php"));


    $subdir = str_replace(cbNewsletter_DIC::get("basedir"), "", cbNewsletter_DIC::get("calldir"));

    include_once(cbNewsletter_checkout($subdir . "/lib/bootstrap.database.php"));

    include_once(cbNewsletter_checkout("/lib/bootstrap.maintenance.php"));

  } else {

    $cbNewsletter_Debugout->add("skipping database bootstrapping...");

  }




  $cbNewsletter_Debugout->add("</pre>");


?>
