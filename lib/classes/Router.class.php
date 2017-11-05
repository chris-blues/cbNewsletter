<?php

class Router {

  protected $routes = array();



  public static function load($file) {

    $Router = new static;

    $Router->routes = include_once(checkout($file));

    return $Router;

  }

  public function direct($view) {

    global $Debugout, $error;

    if (array_key_exists($view, $this->routes)) {

      $route = $this->routes[$view];

      $Debugout->add(
        "directing known view '" . $view . "' to",
        $route
      );

      return $route;

    } else {

      $route = $this->routes[""];

      $error["routing"]["invalid_route"] = $view;

      $Debugout->add(
        "directing unknown view '" . $view . "' to error page"
      );

      return "/views/error.view.php";

    }

  }

}

?>
