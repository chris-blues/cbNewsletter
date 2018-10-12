<?php

class cbNewsletter_Router {

  protected $routes = array();



  public static function load($file) {

    $cbNewsletter_Router = new static;

    $cbNewsletter_Router->routes = include_once(cbNewsletter_checkout($file));

    return $cbNewsletter_Router;

  }

  public function direct($view) {

    global $cbNewsletter_Debugout, $error;

    if (array_key_exists($view, $this->routes)) {

      $route = $this->routes[$view];

      $cbNewsletter_Debugout->add(
        "directing known view '" . $view . "' to",
        $route
      );

      return $route;

    } else {

      $route = $this->routes[""];

      $error["routing"]["invalid_route"] = $view;

      $cbNewsletter_Debugout->add(
        "directing unknown view '" . $view . "' to error page"
      );

      return "/views/error.view.php";

    }

  }

}

?>
