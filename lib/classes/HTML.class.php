<?php

class HTML {

  public function infobox($content, $class = "") {

    if (strlen($class) > 0) $class = "cbNewsletter_info shadow " . $class;
    else $class = "cbNewsletter_info shadow";

    $result = "      <div class=\"" . $class . "\">\n";

    $result .= "        " . $content . "\n";

    $result .= "      </div>\n";

    return $result;

  }

  public function errorbox ($content) {

    $result = "      <div class=\"cbNewsletter_errors shadow\">\n";

    $result .= "        " . $content . "\n";

    $result .= "      </div>\n";

    return $result;

  }

  public function ul($list) {

    if (!is_array($list)) {
      return $this->errorbox("\$HTML->ul(): " . gettext("This is not an array!"));
    } else {

      $result = "    <ul>\n";

      foreach ($list as $value) {

        $result .= "      <li>" . $value . "</li>\n";

      }

      $result .= "    </ul>\n";

    }

    return $result;

  }

}

?>
