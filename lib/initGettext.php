<?php

  $cbNewsletter_Debugout->add("<pre><b>[ initGettext ]</b>");

  unset($locale);

  // cbNewsletter_DIC::get("general")["language"] overrides everything
  if (cbNewsletter_DIC::get("general")["language"] != "") {
    $locale = cbNewsletter_DIC::get("general")["language"];
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
          case "fr": $locale = "fr_FR"; break;
          case "es": $locale = "es_ES"; break;

        }

        $result = "HTTP_ACCEPT_LANGUAGE";

      }

    }

  }

  // if all fails, use "en_GB"! (actually use inline gettext strings)
  switch ($locale) {

    case "de":    $locale = "de_DE"; break;
    case "de_DE": $locale = "de_DE"; break;

    case "fr":    $locale = "fr_FR"; break;
    case "fr_FR": $locale = "fr_FR"; break;

    case "es":    $locale = "es_ES"; break;
    case "es_ES": $locale = "es_ES"; break;

    case "en":    $locale = "en_GB"; break;
    case "en_GB": $locale = "en_GB"; break;

    default:      $locale = "en_GB"; $result = "default"; break;

  }

  $cbNewsletter_Debugout->add("Got locale from", $result);

  $directory = realpath(cbNewsletter_DIC::get("basedir") . "/locale");
  $textdomain = "cbNewsletter";
  cbNewsletter_DIC::add("locale", $locale);
  $localeName = $locale . ".utf8";

  $bindtextdomain = bindtextdomain($textdomain, $directory);
  $localeString   = setlocale(LC_MESSAGES, $localeName) . " -> ";
  $settextdomain  = textdomain($textdomain);
  $localeString   .= bind_textdomain_codeset($textdomain, 'UTF-8');




  $cbNewsletter_Debugout->add("bind_textdomain", $bindtextdomain);
  $cbNewsletter_Debugout->add("textdomain", $settextdomain);
  $cbNewsletter_Debugout->add("locale", $localeString);


  $cbNewsletter_Debugout->add("</pre>");

?>
