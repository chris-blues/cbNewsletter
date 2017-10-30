<?php

class Email {

  protected $name;
  protected $to;
  protected $from;
  protected $header;
  protected $subject;
  protected $body;

  public function __construct($job, $data, $locale) {

    if(is_object($data)) $subscriber = $data->getdata();
    elseif(is_array($data)) $subscriber = $data;

    $this->name    = $subscriber["name"];
    $this->to      = $subscriber["email"];
    $this->from    = "newsletter@" . $_SERVER["SERVER_NAME"];

    $this->subject = $this->assemble_subject($job);
    $this->header  = $this->assemble_header();
    $this->body    = $this->assemble_body($job, $subscriber, $locale);

  }

  public function send_mail() {

    if (!mail($this->to, $this->subject, $this->body, $this->header)) {
      return false;
    } else { return true; }

  }

  private function assemble_header() {

    $header  = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
    $header .= "Content-Transfer-Encoding: 8bit\r\n";
    $header .= "From: " . $this->from . "\r\n";
    $header .= "Date: " . date(DATE_RFC2822) . "\r\n";
    $header .= "\r\n";
    return $header;
  }

  private function assemble_subject($job) {

    switch($job) {

      case "opt_in": {
        return sprintf(gettext("Please verify your subscription to our newsletter @ %s"), $_SERVER["SERVER_NAME"]);
        break;
      }

      case "opt_out": {
        return sprintf(gettext("Please verify your unsubscription to our newsletter @ %s"), $_SERVER["SERVER_NAME"]);
        break;
      }

    }

  }

  private function assemble_body($job, $subscriber, $locale) {

    $link_url = "";
    if ($_SERVER["HTTPS"] == "on") $link_url .= "https://";
    else                           $link_url .= "http://";
    $link_url .= $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];

    $link_manage = $link_url;
    $link_manage .= assembleGetString(
      "string",
      array(
	"view" => "manage_subscription",
	"id" => $subscriber["id"],
	"hash" => $subscriber["hash"],
	"standalone" => "",
	"job" => "",
      )
    );
    $link_manage .= "#cbNewsletter_mainBox";
    $link_manage = str_replace("&amp;", "&", $link_manage);



    $search = array("%name%", "%server%");
    $replace = array($this->name, $_SERVER["SERVER_NAME"]);



    switch($job) {

      case "opt_in": {

        $link_process = $link_url;
        $link_process .= assembleGetString(
	  "string",
	  array(
	    "view" => "verify_subscription",
	    "id" => $subscriber["id"],
	    "hash" => $subscriber["hash"],
	    "standalone" => "",
	    "job" => "",
	  )
	);
	$link_process .= "#cbNewsletter_mainBox";
        $link_process = str_replace("&amp;", "&", $link_process);

        $body = str_replace(
          $search,
          $replace,
          file_get_contents(
            realpath(dirname(__FILE__) . "/../../views/mail.opt_in." . $locale . ".txt")
	  )
	);
        break;
      }

      case "opt_out": {

        $link_process = $link_url;
        $link_process .= assembleGetString(
	  "string",
	  array(
	    "view" => "verify_unsubscription",
	    "id" => $subscriber["id"],
	    "hash" => $subscriber["hash"],
	    "standalone" => "",
	    "job" => "",
	  )
	);
	$link_process .= "#cbNewsletter_mainBox";
        $link_process = str_replace("&amp;", "&", $link_process);

        $body = str_replace(
          $search,
          $replace,
          file_get_contents(
            realpath(dirname(__FILE__) . "/../../views/mail.opt_out." . $locale . ".txt")
	  )
	);
        break;
      }

    }



    $body = wordwrap($body, 70);

    $search = array("%link_process%", "%link_manage%");
    $replace = array($link_process, $link_manage);
    $body = str_replace($search, $replace, $body);

    return $body;

  }

}

?>
