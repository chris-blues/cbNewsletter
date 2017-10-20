<?php

class HTML {

  public function infobox($content, $class = "") {

    if (strlen($class) > 0) $class = "cbNewsletter_info shadow " . $class;
    else $class = "cbNewsletter_info shadow";

    echo "      <div class=\"" . $class . "\">\n";

    echo "        " . $content . "\n";

    echo "      </div>\n";

  }

  public function errorbox ($content) {

    echo "      <div class=\"cbNewsletter_errors shadow\">\n";

    echo "        " . $content . "\n";

    echo "      </div>\n";

  }

}

?>
