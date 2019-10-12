<?php

class cbNewsletter_Request {

  public static function view() {

    global $cbNewsletter_Debugout;

    if (isset($_GET["view"])) {

      $view = $_GET["view"];

    } else {

      $view = "";

    }

    return $view;

  }

}

?>
