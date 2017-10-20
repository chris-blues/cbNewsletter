<?php

  if ($debug) {
    echo "<b>[ routing ] [ view ]</b>: ";
    if (isset($cbNewsletter["config"]["view"])) echo $cbNewsletter["config"]["view"];
    else echo "&lt; none &gt;";
    echo "<br>\n";
  }

// =================== Routing ===================

  if (isset($cbNewsletter["config"]["view"]) and strlen($cbNewsletter["config"]["view"]) > 1) {

    if ($debug) echo "<b>[ routing -&gt; " . $cbNewsletter["config"]["view"] . " ]</b> -&gt; " . realpath($cbNewsletter["config"]["basedir"] . "/lib/actions/" . $cbNewsletter["config"]["view"] . ".action.php") . "<br>\n";
    include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/actions/" . $cbNewsletter["config"]["view"] . ".action.php"));

  } else {

    if ($debug) echo "<b>[ routing -&gt; &lt; none &gt; ]</b> -&gt; " . realpath($cbNewsletter["config"]["basedir"] . "/views/subscription.form.php") . "<br>\n";
    include_once(realpath($cbNewsletter["config"]["basedir"] . "/views/subscription.form.php"));

  }



// =================== Routing ===================

?>
