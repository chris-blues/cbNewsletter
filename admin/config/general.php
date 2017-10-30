<?php

  return array(

    "debug"                => true,
    "debug_level"          => "warn",
    "show_processing_time" => true,
    "language"             => "de_DE",
    "debug_levels"         => array(
      "off"  => 0,
      "warn" => E_ALL & ~E_NOTICE,
      "full" => E_ALL
    ),

  );

?>
