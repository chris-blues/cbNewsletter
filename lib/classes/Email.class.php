<?php

include_once(cbNewsletter_checkout("/admin/lib/classes/PHPMailer.class.php"));

class Email {

    protected $name;
    protected $to;
    protected $from;
    protected $header;
    protected $subject;
    protected $body;
    protected $alt_body;

    public function __construct($job, $data, $locale) {

        if(is_object($data)) $subscriber = $data->getdata();
        elseif(is_array($data)) $subscriber = $data;

        $this->name    = $subscriber["name"];
        $this->to      = $subscriber["email"];
        $this->from    = "newsletter@" . $_SERVER["SERVER_NAME"];

        $this->subject = $this->assemble_subject($job);
//         $this->header  = $this->assemble_header();
        $this->assemble_body($job, $subscriber, $locale);

    }

    public function send_mail() {

        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = "utf-8";
            $mail->isHTML(true);

            $mail->setFrom($this->from);

            $mail->addCustomHeader( $this->assembleHeader("Return-Path") );
            $mail->addCustomHeader( $this->assembleHeader("Precedence") );
            $mail->addCustomHeader( $this->assembleHeader("List-Id") );
            $mail->addCustomHeader( $this->assembleHeader("List-Unsubscribe") );
            $mail->addCustomHeader( $this->assembleHeader("Errors-To") );

            $mail->addAddress($this->to);

            $mail->Subject = $this->subject;

            $mail->Body = "<html><head><style>body { max-width: 99%; padding: 0 5em; font-family: Open-Sans, Ubuntu, Verdana, Arial, sans-serif; }</style></head><body>";
            $mail->Body .= $this->body;
            $mail->Body .= '</body></html>';

            $mail->AltBody = $this->alt_body;

            if ($mail->send()) return true;
            else               return false;

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    private function assembleHeader($type) {
        if     ($type == "Return-Path")        $return = "<>";
        elseif ($type == "Precedence")         $return = "list";
        elseif ($type == "Errors-To")          $return = "newsletter@" . $_SERVER["SERVER_NAME"];
        elseif ($type == "List-Unsubscribe") {
            $return  = "https://" . $_SERVER["SERVER_NAME"];
            $return .= str_replace(array("&amp;", "/admin"), array("&", ""), $_SERVER["PHP_SELF"]);
            $return .= assembleGetString(
                "string",
                array(
                    "view"  => "manage_subscription",
                    "job"   => "unsubscribe",
                    "id"    => $this->subscriber["id"],
                    "hash"  => $this->subscriber["hash"],
                    "agree" => "agree"
                )
            );
        } elseif ($type == "List-Id") {
            $return = "https://" . $_SERVER["SERVER_NAME"] . str_replace("/admin", "", $_SERVER["PHP_SELF"]);
        }
        return $type . ":" . $return;
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


        $search = array("%name%", "%server%", "%server_url%");
        $replace = array($this->name, $_SERVER["SERVER_NAME"], str_replace($_SERVER["PHP_SELF"], "", $link_url));

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

                $alt_body = str_replace(
                    $search,
                    $replace,
                    file_get_contents(
                        realpath(cbNewsletter_DIC::get("basedir") . "/views/mail.opt_in." . cbNewsletter_DIC::get("locale") . ".txt")
                    )
                );
                $body = str_replace(
                    $search,
                    $replace,
                    file_get_contents(
                        realpath(cbNewsletter_DIC::get("basedir") . "/views/mail.opt_in." . cbNewsletter_DIC::get("locale") . ".html")
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

                $alt_body = str_replace(
                    $search,
                    $replace,
                    file_get_contents(
                        realpath(dirname(__FILE__) . "/../../views/mail.opt_out." . $locale . ".txt")
                    )
                );
                $body = str_replace(
                    $search,
                    $replace,
                    file_get_contents(
                        realpath(dirname(__FILE__) . "/../../views/mail.opt_out." . $locale . ".html")
                    )
                );
                break;
            }
        }

//     $body = wordwrap($body, 70);

        $search = array("%link_process%", "%link_manage%");
        $replace = array($link_process, $link_manage);
        $this->body = str_replace($search, $replace, $body);
        $this->alt_body = str_replace($search, $replace, $alt_body);

        return $body;

    }

}

?>
