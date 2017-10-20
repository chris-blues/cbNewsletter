<h3><?php echo gettext("Verify subscription"); ?></h3>

<?php

  include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"));



// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    if ($debug) echo "<b>[ verify_subscription.action ]</b> Transferred data seems fine so far! Checking against the database!<br>\n";

    $result = $query->check_subscription($data["id"], $data["hash"]);

    if ($debug) {
      echo "<b>[ verify_subscription.action ]</b> $result entry found which matches this data.<br>\n";
    }

    if (intval($result) == 1) {

      $already_verified = $query->check_already_verified($data["id"], $data["hash"]);
      if ($already_verified) {

        $HTML->errorbox(gettext("This email has already been verified. No need to do it again. Aborting!") . "<br>\n");

      } else {

        if ($debug) { echo "<b>[ verify_subscription.action ]</b> Not verified yet. Updating database... "; }

        $result = $query->verify_subscription($data["id"], $data["hash"]);

        if ($debug) {
          if ($result) echo "[ OK ]<br>\n";
          else {
            echo "Error!<br>\n";
	  }
        }

        if ($result) {

          $HTML->infobox(gettext("Thank you very much! Your subscription is now verified and active.") . "<br>\n");

        } else {

          $error["database"]["error"] = true;
	  $error["database"]["update_verified"]["error"] = true;
	  $error["database"]["update_verified"]["data"] = $result;

        }
      }
    } else {

      $error["verification"]["error"] = true;
      $error["verification"]["hash"]["error"] = true;
      $error["verification"]["hash"]["data"] = "not set";

      echo "<div class=\"errors shadow\">";
      echo gettext("Sorry! The link seems to be broken! Please try again - and make sure you have the complete link!<br>\n");
      echo "</div>\n";

    }

  }

// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/actions/manage_subscription.action.php"));

  }

?>
