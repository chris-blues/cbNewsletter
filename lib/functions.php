<?php

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



function cleanupErrors($errors, $bubble = false) {

  if (isset($errors) and $errors != "empty") {
    foreach ($errors as $key => $value) {
      if (is_array($value)) {
        $errors = cleanupErrors($value, true);
        if ($errors == "empty") {
          unset($errors);
        }
      } else {
        if ($value == false or $value == "") {
          unset($errors[$key]);
        }
      }
    }
  }

  if (isset($errors) and count($errors) < 1) unset($errors);

  if (isset($errors)) return $errors;
  else return "empty";

}

function cbNewsletter_showErrors($errors, $bubble = false) {

  if (!$bubble) {

    // cleanup false positives first! Exit function if nothing is left.
    $errors = cleanupErrors($errors);
    if ($errors == "empty") return;

    echo "<div class=\"errors shadow\">\n";
    echo "  <b>Errors:</b>\n";
  }
  echo "  <ul>\n";

  foreach ($errors as $key => $error) {
    if (!is_bool($error)) { echo "    <li>"; }
    if (is_array($error)) {
      echo "<b>{$key}</b>";
      cbNewsletter_showErrors($error, true);
    } else {
      if (!is_bool($error)) {
        echo "<b>{$key}:</b> $error";
      }
    }
    if (!is_bool($error)) { echo "</li>\n"; }
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
  } else {
    return sprintf("%5.3f s", $newTime);
  }

  // input is more than seconds
  if ($input > 60) {
    $minutes = floor($input / 60);
    $seconds = $input - ($minutes * 60);

    if ($minutes > 60) {
      $hours = floor($minutes / 60);
      $minutes = $minutes - ($hours * 60);

      return sprintf("%2d:%2d:%2d", $hours, $minutes, $seconds);
    }

    return sprintf("%2d:%2d", $minutes, $seconds);
  }
}

?>
