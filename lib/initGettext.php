<?php

$locales = scandir(realpath($cbNewsletter["config"]["basedir"] . "/locale"));
foreach ($locales as $key => $language) {
  if ($language == "." or $language == ".." or !is_dir($language)) unset($locales[$key]);
}

// $cbNewsletter["config"]["language"] overrides everything
if ($cbNewsletter["config"]["language"] != "") $locale = $cbNewsletter["config"]["language"];
else {
  // if we have some user-setting from the URI then use this
  if (isset($_GET["lang"])) $locale = $_GET["lang"];
  else {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      // if still nothing, try browser preference
      $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      switch ($lang) {
        case "de": $locale = "de_DE"; break;
        case "en": $locale = "en_GB"; break;
      }
    }
  }
}
// if all fails, use "en_GB"! (actually use inline gettext strings)
if (!isset($locale) or $locale == "") $locale = "en_GB";

if ($locale == "de") $locale = "de_DE";
if ($locale == "en") $locale = "en_GB";

$directory = realpath($cbNewsletter["config"]["basedir"] . "/locale");
$textdomain = "cbNewsletter";
$cbNewsletter["config"]["locale"] = $locale;
$localeName = $locale . ".utf8";

$bindtextdomain = bindtextdomain($textdomain, $directory);
$localeString = setlocale(LC_MESSAGES, $localeName) . " -> ";
$settextdomain = textdomain($textdomain);
$localeString .= bind_textdomain_codeset($textdomain, 'UTF-8');

if ($debug) {
  echo $HTML->infobox(
    "bind_textdomain: " . $bindtextdomain . "<br>\ntextdomain: " . $settextdomain . "<br>\nlocale: " . $localeString,
    "debug"
  );
}

?>
