<?php

function findData($var) {

  if (isset($_GET[$var])) {

    return $_GET[$var];

  } elseif (isset($_POST[$var])) {

    return $_POST[$var];

  } else {

    return false;

  }

}

// accepted $method is either "array" or "string". An emtpy string defaults to "string"
function assembleGetString($method = "", $newVars = array()) {
  if (isset($_GET)) {
    $counter = 0;
    foreach ($_GET as $key => $value) {
      if ($value == "") continue;
      $tmpArray[$key] = $value;
    }
  }
  if (isset($newVars)) {
    foreach ($newVars as $key => $value) {
      if ($value == "") {
        unset($tmpArray[$key]);
        continue;
      }
      $tmpArray[$key] = $value;
      }
    }

  if ($method == "array") return $tmpArray;

  if ($method == "" or $method == "string") {
    if (isset($tmpArray) and count($tmpArray) > 0) {
      foreach ($tmpArray as $key => $value) {
        if ($counter == 0) $GETString = "?{$key}={$value}";
        else               $GETString .= "&amp;{$key}={$value}";
        $counter++;
      }
    }
    else $GETString = "";
  }

  return $GETString;
}

if (!function_exists("dump_var")) {
  function dump_var($var, $depth = 0) {

    $indentation = str_pad("", 4 * $depth);

    $overallType = gettype($var);
    if (is_array($var)) $overallType .= " (" . count($var) . ")";

    if ($depth == 0) echo "(" . gettype($var) . ")\n<pre class=\"shadow\">";

    if (is_array($var)) {

      foreach ($var as $key => $value) {

        echo $indentation . "[" . $key . "] => \n";
        dump_var($value, $depth + 1);

      }

    } else {
      echo $indentation; var_dump($var);
    }

    if ($depth == 0) echo "</pre>\n";

  }

}





function cbNewsletter_showErrors($errors, $bubble = false) {

  if (!$bubble) {

    echo "<div class=\"errors shadow\">\n";
    echo "  <b>Errors:</b>\n";

  }

  echo "  <ul>\n";

  foreach ($errors as $key => $error) {

    echo "    <li>";

    if (is_array($error)) {

      echo "      <b>{$key}:</b>";
      cbNewsletter_showErrors($error, true);

    } else {

      if (is_bool($error)) { $error = ($error) ? "true" : "false"; }
      echo "      <b>{$key}:</b> $error";

    }

    echo "    </li>\n";

  }

  echo "  </ul>\n";
  if (!$bubble) {
    echo "</div>\n";
  }

}

function prettyTime ($input) {

  // expects seconds (float) as input

  $newTime = round($input, 9);

  // input is less than a second
  if ($newTime < 1) {
    $milli = $newTime * 1000;
    $newTime = $milli;

    if ($milli < 1) {
      $micro = $milli * 1000;
      $newTime = $micro;

      if ($newTime < 1) {
        $nano = $milli * 1000;
        $newTime = $nano;
        return sprintf("%5.3f ns", $newTime);
      } else {
        return sprintf("%d µs", $newTime);
      }
    } else {
      return sprintf("%5.3f ms", $newTime);
    }

  } elseif ($input >= 60) {

    $minutes = floor($input / 60);
    $seconds = $input - ($minutes * 60);

    if ($minutes >= 60) {
      $hours = floor($minutes / 60);
      $minutes = $minutes - ($hours * 60);

      if ($hours >= 24) {
        $days = floor($hours / 24);
        $hours = $hours - ($days * 24);

        return sprintf("%2dd %02dh %02dm %02ds", $days, $hours, $minutes, $seconds);
      }

      return sprintf("%2dh %02dm %02ds", $hours, $minutes, $seconds);
    }

    return sprintf("%2dm %02ds", $minutes, $seconds);

  } else {

    return sprintf("%5.3f s", $newTime);
  }
}

?>
