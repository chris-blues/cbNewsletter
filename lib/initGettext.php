<?php

  $Debugout->add("<pre><b>[ initGettext ]</b>");



  // $cbNewsletter["config"]["general"]["language"] overrides everything
  if ($cbNewsletter["config"]["general"]["language"] != "") {

    $locale = $cbNewsletter["config"]["general"]["language"];
    $result = "/admin/config/general.php";

  } else {

    // if we have some user-setting from $_GET then use this
    if (isset($_GET["lang"])) {

      $locale = $_GET["lang"];
      $result = "\$_GET";

    }

    if (!isset($locale)) {

      if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

        // if still nothing, try browser preference
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        switch ($lang) {

          case "de": $locale = "de_DE"; break;
          case "en": $locale = "en_GB"; break;

        }

        $result = "HTTP_ACCEPT_LANGUAGE";

      }

    }

  }

  // if all fails, use "en_GB"! (actually use inline gettext strings)
  switch ($locale) {

    case "de":    $locale = "de_DE"; break;
    case "de_DE": $locale = "de_DE"; break;

    case "en":    $locale = "en_GB"; break;
    case "en_GB": $locale = "en_GB"; break;

    default:      $locale = "en_GB"; $result = "default"; break;

  }

  $Debugout->add("Got locale from", $result);

  $directory = realpath($cbNewsletter["config"]["basedir"] . "/locale");
  $textdomain = "cbNewsletter";
  $cbNewsletter["config"]["general"]["locale"] = $locale;
  $localeName = $locale . ".utf8";

  $bindtextdomain = bindtextdomain($textdomain, $directory);
  $localeString   = setlocale(LC_MESSAGES, $localeName) . " -> ";
  $settextdomain  = textdomain($textdomain);
  $localeString   .= bind_textdomain_codeset($textdomain, 'UTF-8');




  $Debugout->add("bind_textdomain", $bindtextdomain);
  $Debugout->add("textdomain", $settextdomain);
  $Debugout->add("locale", $localeString);


  $Debugout->add("</pre>");

?>
