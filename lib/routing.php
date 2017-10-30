<?php

  $debugout .= "<pre><b>[ routing ]</b>\n";

// =================== Routing ===================

// ================  pre display  ================





// ================  pre display  ================





  if (isset($cbNewsletter["config"]["view"]) and strlen($cbNewsletter["config"]["view"]) > 1) {

    $debugout .= str_pad("including /actions/" . $_GET["view"] . ".action.php ", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/actions/" . $_GET["view"] . ".action.php"))) ? : $debugout .= "FAILED\n";

  } else {

    $debugout .= str_pad("including /admin/actions/subscription.form.php ", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/subscription.form.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";

  }





// ===============  post display  ================






// ===============  post display  ================

// =================== Routing ===================

?>
