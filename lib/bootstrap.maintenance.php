<?php

  $cbNewsletter_Debugout->add("<pre><b>[ bootstrap.maintenance ]</b>");

  include_once(cbNewsletter_checkout("/lib/classes/Maintenance.class.php"));


  $tables_maintenance = $cbNewsletter_query->get_maintenance_data();

  $cbNewsletter_Debugout->add("Checking for table maintenance cycles...");

  foreach ($tables_maintenance as $key => $table) {

    $tablename = $table->get_name();



    if ($table->needs_maintenance()) {

      $result = "Optimizing table... ";

      if ($cbNewsletter_query->optimize_table($tablename)) {

        $result .= "OK";

      } else {

        $result .= "FAILED\n";

      }

    } else {

      $result = "Skipping maintenance...";

    }

    $cbNewsletter_Debugout->add(
      " * Table " . str_pad($tablename, 25) . " is " . prettyTime($table->get_last_optimization(false)) . "  into its maintenance cycle",
      $result
    );

  }


  $cbNewsletter_Debugout->add(
    "Removing unverified subscriptions older than 30 days",
    $cbNewsletter_query->remove_unverified_subsciptions() . " expired subscriptions deleted"
  );

  $cbNewsletter_Debugout->add("</pre>");

?>
