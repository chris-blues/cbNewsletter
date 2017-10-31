<?php

class Maintenance {

  protected $name;
  protected $ctime;
  protected $optimized;

  public function get_last_optimization($pretty = false) {

    $timespan = time() - $this->optimized;

    $HTML = new HTML;

    if ($pretty === true)
      return "Database table " . $HTML->code($this->name) . " has been optimized " . $HTML->code(prettyTime($timespan)) . " ago.<br>\n";
    else
      return $timespan;

  }

  public function needs_maintenance() {

    $timespan = time() - $this->optimized;

    $timespan_needsMaintenance = 30 * 86400;

    if ($timespan > $timespan_needsMaintenance) return true;
    else return false;

  }

  public function get_name() {

    return $this->name;

  }

}

?>