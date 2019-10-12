<?php

  return array(

    "debug"                => true,
    "debug_level"          => "full",
    "show_processing_time" => true,
    "language"             => "",
    "debug_levels"         => array(
      "off"  => 0,
      "warn" => E_ALL & ~E_NOTICE,
      "full" => E_ALL
    ),

  );

?>
