<?php

class Request {

  public static function view() {

    global $Debugout;

    if (isset($_GET["view"])) {

      $view = $_GET["view"];

    } else {

      $view = "";

    }

    return $view;

  }

}

?>
