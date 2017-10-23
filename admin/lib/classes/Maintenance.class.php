<?php

class Maintenance {

  protected $name;
  protected $ctime;
  protected $optimized;

  public function get_last_optimization() {

    $timespan = time() - $this->optimized;

    $HTML = new HTML;

    return $this->name . " has been optimized " . $HTML->code(prettyTime($timespan)) . " ago.<br>\n";

  }

  public function needs_maintenance() {

    $timespan = time() - $this->optimized;

    if ($timespan > 1209600) return true;
    else return false;

  }

  public function get_name() {

    return $this->name;

  }

}

?>
