<?php

  $debugout .= "<pre><b>[ bootstrap.maintenance ]</b>\n";

  $debugout .= str_pad("including /lib/classes/Maintenance.class.php ", 90);
  (include_once(realpath($cbNewsletter["config"]["basedir"] . "/lib/classes/Maintenance.class.php"))) ? $debugout .= "OK\n" : $debugout .= "FAILED\n";


  $tables_maintenance = $query->get_maintenance_data();

  $debugout .= "Checking for table maintenance cycles...\n";

  foreach ($tables_maintenance as $key => $table) {

    $tablename = $table->get_name();

    $debugout .= str_pad(" * Table " . str_pad($tablename, 25) . " is " . prettyTime($table->get_last_optimization(false)) . "  into its maintenance cycle", 90);

    if ($table->needs_maintenance()) {

      $debugout .= "Optimizing table... ";

      if ($query->optimize_table($tablename)) {
        $debugout .= "OK\n";

      } else {
        $debugout .= "FAILED\n";

      }

    } else {

      $debugout .= "Skipping maintenance...\n";

    }

  }


  $debugout .= str_pad("Removing unverified subscriptions older than 30 days", 90);
  $debugout .= $query->remove_unverified_subsciptions() . " expired subscriptions deleted\n";

  $debugout .= "</pre>\n";

?>
