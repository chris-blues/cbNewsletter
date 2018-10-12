<?php

class cbNewsletter_Debugout {

  protected $debug_message;

  public function __construct() {

    $this->debug_message = "";

    if (isset($_GET) and count($_GET) > 0) {
    $this->add(
      "<pre><b>\$_GET</b> ",
      print_r($_GET, true) . "</pre>\n"
    );
  }
  if (isset($_POST) and count($_POST) > 0) {
    $this->add(
      "<pre><b>\$_POST</b> ",
      print_r($_POST, true) . "</pre>\n"
    );
  }

  }

  public function __toString() {

    return "This Debugout is " . strlen($this->debug_message) . " chars long.";

  }

  public function add($event = "", $result = "") {

    if (strlen($result) < 1) {

      $this->debug_message .= $event . "\n";

    } else {

      $this->debug_message .= str_pad($event, 90) . $result . "\n";

    }

  }

  public function output ($plaintext = false) {

    if ($plaintext === true) return strip_tags($this->debug_message);
    else return "<pre>" . $this->debug_message . "</pre>\n";


  }


}

?>
