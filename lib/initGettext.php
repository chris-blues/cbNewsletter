<?php

  $debugout .= "<pre><b>[ initGettext ]</b>\n";

$locales = scandir(realpath($cbNewsletter["config"]["basedir"] . "/locale"));
foreach ($locales as $key => $language) {
  if ($language == "." or $language == ".." or !is_dir($language)) unset($locales[$key]);
}

$debugout .= str_pad("Got locale from",90);

// $cbNewsletter["config"]["general"]["language"] overrides everything
if ($cbNewsletter["config"]["general"]["language"] != "") {

  $locale = $cbNewsletter["config"]["general"]["language"];
  $debugout .= "/admin/config/general.php\n";

} else {

  // if we have some user-setting from $_GET then use this
  if (isset($_GET["lang"])) {

    $locale = $_GET["lang"];
    $debugout .= "\$_GET\n";

  }

  if (!isset($locale)) {

    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

      // if still nothing, try browser preference
      $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      switch ($lang) {

        case "de": $locale = "de_DE"; break;
        case "en": $locale = "en_GB"; break;

      }

      $debugout .= "HTTP_ACCEPT_LANGUAGE\n";

    }

  }

}

// if all fails, use "en_GB"! (actually use inline gettext strings)
switch ($locale) {

  case "de":    $locale = "de_DE"; break;
  case "de_DE": $locale = "de_DE"; break;

  case "en":    $locale = "en_GB"; break;
  case "en_GB": $locale = "en_GB"; break;

  default:      $locale = "en_GB"; break;

}

$directory = realpath($cbNewsletter["config"]["basedir"] . "/locale");
$textdomain = "cbNewsletter";
$cbNewsletter["config"]["general"]["locale"] = $locale;
$localeName = $locale . ".utf8";

$bindtextdomain = bindtextdomain($textdomain, $directory);
$localeString = setlocale(LC_MESSAGES, $localeName) . " -> ";
$settextdomain = textdomain($textdomain);
$localeString .= bind_textdomain_codeset($textdomain, 'UTF-8');




$debugout .= str_pad("bind_textdomain", 90) . $bindtextdomain . "\n";
$debugout .= str_pad("textdomain", 90) . $settextdomain . "\n";
$debugout .= str_pad("locale", 90) . $localeString . "\n";


$debugout .= "</pre>";

?>
