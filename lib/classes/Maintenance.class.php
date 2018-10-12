<?php

class Maintenance {

  protected $name;
  protected $ctime;
  protected $optimized;

  public function get_last_optimization($pretty = false) {

    global $cbNewsletter_HTML;

    $timespan = time() - $this->optimized;

    if ($pretty === true)
      return "Database table " . $cbNewsletter_HTML->code($this->name) . " has been optimized " . $cbNewsletter_HTML->code(prettyTime($timespan)) . " ago.<br>\n";
    else
      return $timespan;

  }

  public function needs_maintenance() {

    $timespan = time() - $this->optimized;

    // 86400 = 24 * 60 * 60 = 1 day
    $timespan_needsMaintenance = 30 * 86400;

    if ($timespan > $timespan_needsMaintenance) return true;
    else return false;

  }

  public function get_name() {

    return $this->name;

  }

}

?>
