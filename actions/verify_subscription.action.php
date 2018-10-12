<h3><?php echo gettext("Verify subscription"); ?></h3>

<?php

  $cbNewsletter_Debugout->add("<pre><b>[ verify_subscription ]</b>");

  include_once(cbNewsletter_checkout("/lib/verify.transmitted_data.php"));



// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    $result = $cbNewsletter_query->check_subscription($data["id"], $data["hash"]);

    if (intval($result) === 1) {

      $cbNewsletter_Debugout->add("verifying data against the database", "passed");

      $already_verified = $cbNewsletter_query->check_already_verified($data["id"], $data["hash"]);
      if ($already_verified) {

        echo $cbNewsletter_HTML->errorbox(gettext("This email has already been verified. No need to do it again.") . "<br>\n");
        $cbNewsletter_Debugout->add("checking verification status", "already verified! Aborting!");

      } else {

        $cbNewsletter_Debugout->add("checking verification status", "not verified yet...");

        $result = $cbNewsletter_query->verify_subscription($data["id"], $data["hash"]);

        if ($result) {

          echo $cbNewsletter_HTML->infobox(gettext("Thank you very much! Your subscription is now verified and active.") . "<br>\n");
          $cbNewsletter_Debugout->add("updating verification status", "OK");

        } else {

          $error["database"]["error"] = true;
          $error["database"]["update_verified"]["error"] = true;
          $error["database"]["update_verified"]["data"] = $result;

          $cbNewsletter_Debugout->add("updating verification status", "FAILED");

        }
      }
    } else {

      $error["verification"]["error"] = true;
      $error["verification"]["hash"]["error"] = true;
      $error["verification"]["hash"]["data"] = "not set";

      echo "<div class=\"errors shadow\">";
      echo gettext("Sorry! The link seems to be broken! Please try again - and make sure you have the complete link!<br>\n");
      echo "</div>\n";

      $cbNewsletter_Debugout->add("verifying data against the database", "FAILED");

    }

  }

// ============  verify data against database  ============

  if (!isset($error["verification"]["error"]) or !$error["verification"]["error"]) {

    include_once(cbNewsletter_checkout("/actions/manage_subscription.action.php"));

  }

?>
