<?php

  $Debugout->add("<pre><b>[ routing ]</b>");

// =================== Routing ===================

// ================  pre display  ================





// ================  pre display  ================





  if (isset($cbNewsletter["config"]["view"]) and strlen($cbNewsletter["config"]["view"]) > 1) {

    include_once(checkout("/actions/" . $_GET["view"] . ".action.php"));

  } else {

    include_once(checkout("/views/subscription.form.php"));

  }





// ===============  post display  ================






// ===============  post display  ================

// =================== Routing ===================

  $Debugout->add("</pre>");

?>
