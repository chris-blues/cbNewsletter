<?php

// Dependency Injection Container
class DIC {

  protected static $data = array();


  // =============  methods  =============

  public static function add($key, $value) {

    static::$data[$key] = $value;

  }

  public static function get($key) {

    if (!array_key_exists($key, static::$data)) {

      throw new Exception(
        "key '" . $key . "' not found in DIC."
      );

    }

    return static::$data[$key];

  }

  public static function unset($key) {

    unset(static::$data[$key]);

  }

}

?>
