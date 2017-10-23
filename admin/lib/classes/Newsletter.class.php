<?php

class Newsletter {

  protected $from;
  protected $subject;
  protected $text;
  protected $subscriber;

  public function __construct($subscriber, $subject, $text) {

    $this->subscriber = $subscriber->getdata();
    $this->from       = "newsletter@" . $_SERVER["SERVER_NAME"];
    $this->subject    = $subject;

    $search  = array(
      "%name%",
      "%server%",
    );
    $replace = array(
      $this->subscriber["name"],
      $_SERVER["SERVER_NAME"],
    );

    $this->text       = str_replace($search, $replace, $text);



    switch($this->subscriber["locale"]) {

      case "de_DE": $this->subscriber["locale"] = "de_DE"; break;
      case "en_GB": $this->subscriber["locale"] = "en_GB"; break;
      default:      $this->subscriber["locale"] = "en_GB"; break;

    }

  }

  private function assemble_link_list() {

    $link = "https://" . $_SERVER["SERVER_NAME"] . str_replace("/admin", "", $_SERVER["PHP_SELF"]);
    return str_replace("&amp;", "&", $link);

  }

  private function assemble_link_unsubscribe() {

    $link = $this->assemble_link_list() . assembleGetString(
                                            "string",
                                            array(
                                              "view"  => "manage_subscription",
                                              "job"   => "unsubscribe",
                                              "id"    => $this->subscriber["id"],
                                              "hash"  => $this->subscriber["hash"],
                                              "agree" => "agree",
					  )
    );
    return str_replace("&amp;", "&", $link);

  }

  private function assemble_header() {

    $to = $this->subscriber["name"] . " <" . $this->subscriber["email"] . ">";

    $header  = "Content-Type: text/plain; charset = \"UTF-8\";\r\n";
    $header .= "Content-Transfer-Encoding: 8bit\r\n";
    $header .= "From: " . $this->from . "\r\n";
    $header .= "To: " . $to . "\r\n";
    $header .= "Return-Path: <>\r\n";
    $header .= "Precedence: list\r\n";
    $header .= "List-Id: " . $this->from . " <" . $this->assemble_link_list() . ">\r\n";
    $header .= "List-Unsubscribe: <" . $this->assemble_link_unsubscribe() . ">\r\n";
    $header .= "Errors-To: " . $this->from . "\r\n";
    $header .= "Date: " . date(DATE_RFC2822) . "\r\n";
    $header .= "\r\n";
    return $header;

  }

  public function send() {

    $HTML = new HTML;
    $header = $this->assemble_header();

    $search = array(
      "%name%",
      "%server%",
      "%link_unsubscribe%",
      "%link_manage%",
      "&amp;",
    );
    $replace = array(
      $this->subscriber["name"],
      $_SERVER["SERVER_NAME"],
      $this->assemble_link_unsubscribe(),
      $this->assemble_link_list() . assembleGetString(
                                      "string",
                                      array(
                                        "view" => "manage_subscription",
                                        "id"   => $this->subscriber["id"],
                                        "hash" => $this->subscriber["hash"],
                                      )
                                    ),
      "&",
    );

    $text  = wordwrap($this->text, 70);
    $text .= str_replace(
	      $search,
	      $replace,
	      file_get_contents(
		realpath(dirname(__FILE__) . "/../../views/newsletter.footer." . $this->subscriber["locale"] . ".txt")
	      )
	    );


    if (!mail($to, $this->subject, $text, $header)) {

      echo $HTML->errorbox("Error! Could not send newsletter to " . $this->subscriber["name"] . " <" . $this->subscriber["email"] . ">");

    } else {

      echo $HTML->infobox("Newsletter sent to " . $this->subscriber["name"] . " <" . $this->subscriber["email"] . ">");

    }

  }

}

?>
