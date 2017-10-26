<?php

class Maintenance {

  protected $name;
  protected $ctime;
  protected $optimized;

  public function get_last_optimization() {

    $timespan = time() - $this->optimized;

    $HTML = new HTML;

    return "Database table " . $HTML->code($this->name) . " has been optimized " . $HTML->code(prettyTime($timespan)) . " ago.<br>\n";

  }

  public function needs_maintenance() {

    $timespan = time() - $this->optimized;

    $timespan_needsMaintenance = 14 * 86400;

    // development override...
    $timespan_needsMaintenance = 600;

    if ($timespan > $timespan_needsMaintenance) return true;
    else return false;

  }

  public function get_name() {

    return $this->name;

  }

}

?>
