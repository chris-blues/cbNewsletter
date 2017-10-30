<?php

class Config {

  protected $type;
  protected $filename;
  protected $settings;

  public function __construct($type, $settings) {

    $this->type     = $type;
    $this->filename = realpath(__DIR__ . "/../../config/" . $type . ".php");
    $this->settings = $settings;

  }

  public function save_dbSettings() {

    if (!isset($this->settings["driver"]) or strlen($this->settings["driver"]) < 1) {
      $error["switch"] = true;
      $error["data_not_set"]["driver"] = true;
    }

    if (!isset($this->settings["host"]) or strlen($this->settings["host"]) < 1) {
      $error["switch"] = true;
      $error["data_not_set"]["host"] = true;
    }

    if (!isset($this->settings["name"]) or strlen($this->settings["name"]) < 1) {
      $error["switch"] = true;
      $error["data_not_set"]["name"] = true;
    }

    if (!isset($this->settings["user"]) or strlen($this->settings["user"]) < 1) {
      $error["switch"] = true;
      $error["data_not_set"]["user"] = true;
    }

    if (!isset($this->settings["pass"]) or strlen($this->settings["pass"]) < 1) {
      $error["switch"] = true;
      $error["data_not_set"]["pass"] = true;
    }

    if (!isset($error["switch"])) {

      $fhandle = fopen($this->filename, "w");

      if ($fhandle) {

        if (!fwrite($fhandle, "<?php\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "return array(\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "  \"driver\" => \"" . $this->settings["driver"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "  \"host\"   => \"" . $this->settings["host"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "  \"name\"   => \"" . $this->settings["name"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "  \"user\"   => \"" . $this->settings["user"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "  \"pass\"   => \"" . $this->settings["pass"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "  \"options\" => array(\n    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING\n  )\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, ");\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;
        if (!fwrite($fhandle, "?>\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fclose($fhandle)) $error["fclose"]["errmsg"] = "Error closing file " . $this->filename;

        if (!chmod($this->filename, 0775)) $error["chmod"]["errmsg"] = "Error chmod'ing file " . $this->filename;

      } else {

        $error["fopen"]["errmsg"] = "Could not open file " . $this->filename;

      }

    }

    if (isset($error)) return $error;
    else return true;

  }

  public function save_generalSettings() {

    if (isset($this->settings["debug"]) and $this->settings["debug"] == "on")
      $this->settings["debug"] = "true";
    else
      $this->settings["debug"] = "false";

    if (isset($this->settings["show_processing_time"]) and $this->settings["show_processing_time"] == "on")
      $this->settings["show_processing_time"] = "true";
    else
      $this->settings["show_processing_time"] = "false";

    if (!isset($this->settings["debug_level"])) {
      $error["switch"] = true;
      $error["data_not_set"]["debug_level"] = true;
    }

    if (!isset($this->settings["language"])) {
      $error["switch"] = true;
      $error["data_not_set"]["language"] = true;
    }

    if (!isset($error["switch"])) {

      $fhandle = fopen($this->filename, "w");

      if ($fhandle) {

        if (!fwrite($fhandle, "<?php\n\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "  return array(\n\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "    \"debug\"                => " . $this->settings["debug"] . ",\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "    \"debug_level\"          => \"" . $this->settings["debug_level"] . "\",\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "    \"show_processing_time\" => " . $this->settings["show_processing_time"] . ",\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "    \"language\"             => \"" . $this->settings["language"] . "\",\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "    \"debug_levels\"         => array(\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "      \"off\"  => 0,\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "      \"warn\" => E_ALL & ~E_NOTICE,\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "      \"full\" => E_ALL\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "    ),\n\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "  );\n\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fwrite($fhandle, "?>\n"))
          $error["fwrite"]["errmsg"] = "Could not write to file " . $this->filename;

        if (!fclose($fhandle))
          $error["fclose"]["errmsg"] = "Error closing file " . $this->filename;

        if (!chmod($this->filename, 0775))
          $error["chmod"]["errmsg"] = "Error chmod'ing file " . $this->filename;

      } else {

        $error["fopen"]["errmsg"] = "Could not open file " . $this->filename;

      }

    }

    if (isset($error)) return $error;
    else return true;

  }

}

?>
