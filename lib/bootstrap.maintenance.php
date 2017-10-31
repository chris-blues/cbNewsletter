<?php

  $Debugout->add("<pre><b>[ bootstrap.maintenance ]</b>");

  include_once(checkout("/lib/classes/Maintenance.class.php"));


  $tables_maintenance = $query->get_maintenance_data();

  $Debugout->add("Checking for table maintenance cycles...");

  foreach ($tables_maintenance as $key => $table) {

    $tablename = $table->get_name();



    if ($table->needs_maintenance()) {

      $result = "Optimizing table... ";

      if ($query->optimize_table($tablename)) {

        $result .= "OK";

      } else {

        $result .= "FAILED\n";

      }

    } else {

      $result = "Skipping maintenance...";

    }

    $Debugout->add(
      " * Table " . str_pad($tablename, 25) . " is " . prettyTime($table->get_last_optimization(false)) . "  into its maintenance cycle",
      $result
    );

  }


  $Debugout->add(
    "Removing unverified subscriptions older than 30 days",
    $query->remove_unverified_subsciptions() . " expired subscriptions deleted"
  );

  $Debugout->add("</pre>");

?>
