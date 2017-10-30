<h3><?php echo gettext("Verify subscription"); ?></h3>

<?php

  $debugout .= "<pre><b>[ verify_subscription ]</b>\n";

  $debugout .= str_pad("including /lib/verify.transmitted_data.php", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/verify.transmitted_data.php"))) ? : $debugout .= "FAILED\n";



// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    $result = $query->check_subscription($data["id"], $data["hash"]);

    $debugout .= str_pad("verifying data against the database", 90);

    if (intval($result) === 1) {

      $debugout .= "passed\n";

      $debugout .= str_pad("checking verification status",90);
      $already_verified = $query->check_already_verified($data["id"], $data["hash"]);
      if ($already_verified) {

        echo $HTML->errorbox(gettext("This email has already been verified. No need to do it again. Aborting!") . "<br>\n");
        $debugout .= "already verified! Aborting!\n";

      } else {

        $debugout .= "not verified yet...\n";

        $debugout .= str_pad("updating verification status", 90);
        $result = $query->verify_subscription($data["id"], $data["hash"]);

        if ($result) {

          echo $HTML->infobox(gettext("Thank you very much! Your subscription is now verified and active.") . "<br>\n");
          $debugout .= "OK\n";

        } else {

          $error["database"]["error"] = true;
          $error["database"]["update_verified"]["error"] = true;
          $error["database"]["update_verified"]["data"] = $result;

          $debugout .= "FAILED\n";

        }
      }
    } else {

      $error["verification"]["error"] = true;
      $error["verification"]["hash"]["error"] = true;
      $error["verification"]["hash"]["data"] = "not set";

      echo "<div class=\"errors shadow\">";
      echo gettext("Sorry! The link seems to be broken! Please try again - and make sure you have the complete link!<br>\n");
      echo "</div>\n";

      $debugout .= "FAILED\n";

    }

  }

// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    $debugout .= str_pad("including /actions/manage_subscription.action.php", 90);
    (include_once(realpath($cbNewsletter["config"]["basedir"] . "/actions/manage_subscription.action.php"))) ? : $debugout .= "FAILED\n";

  }

?>
