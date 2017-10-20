<?php

class Data {

  protected $id;
  protected $name;
  protected $email;
  protected $new_email;
  protected $address;
  protected $hash;

  protected $types = array(
    "id"        => "id",
    "name"      => "name",
    "email"     => "email",
    "new_email" => "email",
    "address"   => "email",
    "hash"      => "hash",
  );

  public function __toString() {

    return $this->name . " &lt;" . $this->email . "&gt;";

  }

  public function populate() {

    $possibleData = array(
      "id"    => array("id"),
      "name"  => array("name"),
      "email" => array("email", "new_email", "address"),
      "hash"  => array("hash"),
    );

    foreach ($this->types as $name => $type) {

      $result = $this->findData($name);
      if ($result !== false) {

	if ($type == "id") $result = intval($result);

	$validated = $this->validate($name, $result);
	if ($validated !== false) {
	  $this->$name = $validated;
	}

      }

    }

  }

  private function findData($var) {

    if (isset($_GET[$var])) {

      return $_GET[$var];

    } elseif (isset($_POST[$var])) {

      return $_POST[$var];

    } else {

      return false;

    }

  }

  public function getdata() {

    if (isset($this->id)        and $this->id != NULL)        $return["id"]        = $this->id;
    if (isset($this->name)      and $this->name != NULL)      $return["name"]      = $this->name;
    if (isset($this->email)     and $this->email != NULL)     $return["email"]     = $this->email;
    if (isset($this->new_email) and $this->new_email != NULL) $return["new_email"] = $this->new_email;
    if (isset($this->address)   and $this->address != NULL)   $return["address"]   = $this->address;
    if (isset($this->hash)      and $this->hash != NULL)      $return["hash"]      = $this->hash;

    if (count($return) > 0) return $return;
    else return false;

  }

  public function getprop ($property) {

    return $this->$property;

  }

  public function setdata($data) {

    foreach ($data as $name => $value) {

      $this->$name = $value;

    }

  }

  public function validate($name, $var) {

    switch($this->types[$name]) {

      case "id": {

        $tmp = filter_var($var, FILTER_VALIDATE_INT);
        if ($tmp !== false) {

          if (is_int($tmp)) {

            return intval(filter_var($tmp, FILTER_SANITIZE_NUMBER_INT));

	  } else { return false; }

        } else {

          return false;
        }
        break;
      }

      case "hash": {

        $tmp = trim($var);

        if (strlen($tmp) != 64) {
          return false;
        }
        if (!ctype_xdigit($tmp)) {
          return false;
        }

        return $tmp;
        break;
      }

      case "email": {

        $tmp = trim($var);

        if (!filter_var($tmp, FILTER_VALIDATE_EMAIL)) {

          $sPattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
          preg_match($sPattern, $tmp, $aMatch);

          if (isset($aMatch[3]) and filter_var($aMatch[3], FILTER_VALIDATE_EMAIL)) {

            return filter_var($aMatch[3], FILTER_SANITIZE_EMAIL);

          } else {

            return false;

          }

        } else {

          return filter_var($tmp, FILTER_SANITIZE_EMAIL);

        }
        break;
      }

      case "name": {

        $tmp = trim(strip_tags($var));
        return filter_var($tmp, FILTER_SANITIZE_STRING);
        break;
      }

    }

  }

}

?>
