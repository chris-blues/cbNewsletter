<?php

  return array(

    "debug"                => false,
    "debug_level"          => "off",
    "show_processing_time" => true,
    "language"             => "",
    "debug_levels"         => array(
      "off"  => 0,
      "warn" => E_ALL & ~E_NOTICE,
      "full" => E_ALL
    ),

  );

?>
