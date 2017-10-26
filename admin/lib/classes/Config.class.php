<?php

class Config {

  protected $filename;
  protected $settings;

  public function __construct($filename, $settings) {

    $this->filename = $filename;
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

      $dbcredentials = realpath($cbNewsletter["config"]["basedir"] . "/admin/lib") . "/dbcredentials.php";

      $fhandle = fopen($this->filename, "w");

      if ($fhandle) {

        if (!fwrite($fhandle, "<?php\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "return array(\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "  \"driver\" => \"" . $this->settings["driver"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "  \"host\"   => \"" . $this->settings["host"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "  \"name\"   => \"" . $this->settings["name"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "  \"user\"   => \"" . $this->settings["user"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "  \"pass\"   => \"" . $this->settings["pass"] . "\",\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "  \"options\" => array(\n    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING\n  )\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, ");\n\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;
        if (!fwrite($fhandle, "?>\n")) $error["fwrite"]["errmsg"] = "Could not write to file " . $dbcredentials;

        if (!fclose($fhandle)) $error["fclose"]["errmsg"] = "Error closing file " . $dbcredentials;

        if (!chmod($dbcredentials, 0775)) $error["chmod"]["errmsg"] = "Error chmod'ing file " . $dbcredentials;

      } else {

	$error["fopen"]["errmsg"] = "Could not open file " . $dbcredentials;

      }

    }

    if (isset($error)) return $error;
    else return true;

  }

}

?>
