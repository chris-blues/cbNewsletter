<?php

    include_once(cbNewsletter_checkout("/admin/lib/classes/PHPMailer.class.php"));

    class Processor_formdata {

        private $subscriber;
        private $validated;
        private $data;
        private $errors;
        private $output;

        public function __construct($subscriber) {

            $this->subscriber = $subscriber;
            $this->validated = 0;
            $this->data = array();
            $this->errors = array();
            $this->output = array();

            $this->get_post_data();

            if ($this->validated == count($_POST)) {
                $this->validated = true;
            } else {
                $this->validated = false;
            }

            if ($this->validated) {
                $this->output[] = "Data is valid.";
                $this->email(
                    array(
                        "to"  => array(
                            "email" => $this->subscriber["email"],
                            "name" => ucwords($this->subscriber["name"])
                        ),
                        "cc"  => array(),
                        "bcc" => array()
                    )
                );
            } else {
                $this->output[] = "Data is NOT valid!";
                $this->email(
                    array("to" => array("email" => "newsletter@" . $_SERVER["SERVER_NAME"], "name" => "Newsletter return"))
                );
            }

            $this->output[] = "End of process";

        }

        private function get_post_data() {
            $validate_postdata = array(
                "subject",
                "text",
                "name"
            );

            if (isset($_POST) and !empty($_POST)) {
                foreach ($_POST as $key => $value) {

                    if ( !in_array($key, $validate_postdata) ) {
                        $this->errors["validation"][] = $key . " does not belong to this form!";
                        continue;
                    }

                    if ($this->validate($key, $value)) {
                        $this->validated++;
                    } else {
                        $this->errors["validation"][] = "[" . $value . "] did not validate as " . ucfirst($key);
                    }
                }
            } else {
                if (!isset($_POST))     $this->errors["retrieve POST_data"][] = '$_POST not set';
                if (empty($_POST))      $this->errors["retrieve POST_data"][] = '$_POST is empty';
                if (count($_POST) <= 3) $this->errors["retrieve POST_data"][] = '$_POST has too few elements';
            }

            foreach($validate_postdata as $type => $value) {
                if (!isset($this->data[$value])) {
                    $this->errors["validation"][] = $type . " =&gt; " . ucfirst($value) . " is missing!";
                }
            }

        }

        /*
        * params:
        *   $name: string
        *        -> writes to $this->data[$name]
        *   $var: value
        *        -> $this->data[$name] = $var
        *   $addToArray: bool (optional)
        *        -> whether or not the value is an array, to make sure,
        *           we're not overwriting the value with every iteration
        *           $this->data[$name][] = $var
        */
        private function add_data($name, $var, $addToArray = false, $key = NULL) {
            if ($addToArray === true) {
                if ($key === NULL) {
                    $this->data[$name][] = $var;
                } else {
                    $this->data[$name][$key] = $var;
                }
            } else
                $this->data[$name] = $var;
        }

        private function compile_errors() {

            $output = '';

            foreach ($this->errors as $stage => $errors) {
                if (!empty($errors)) {
                    $output .= "<h2>" . $stage . "</h2>\n";
                    $output .= "<ol><li>" . implode("</li><li>", $errors) . "</li></ol>\n";
                }
            }

            return $output;

        }

        private function receiveUpload() {

            include_once(cbNewsletter_checkout("/admin/lib/classes/file.class.php"));
            $error = false;
            foreach ($_FILES["anhang"]["name"] as $key => $value) {
                $filename = dirname(__FILE__) . "/tmp/" . $_FILES["anhang"]["name"][$key];
                if (!move_uploaded_file($_FILES["anhang"]["tmp_name"][$key], $filename)) {
                    $error = true;
                } else {
                    chmod($filename,0755);
                    $return[] = new File (
                        array(
                            "name" => $_FILES["anhang"]["name"][$key],
                            "type" => $_FILES["anhang"]["type"][$key],
                            "size" => $_FILES["anhang"]["size"][$key]
                        )
                    );
                }
            }
            return $return;

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
            }
            elseif ($type == "List-Id") {
                $return = "https://" . $_SERVER["SERVER_NAME"] . str_replace("/admin", "", $_SERVER["PHP_SELF"]);
            }
            return $type . ":" . $return;
        }

        private function email($arg) {
            global $sent;

            $mail = new PHPMailer(true);
            try {
                $mail->CharSet = "utf-8";
                $mail->isHTML(true);

                $mail->setFrom("newsletter@" . $_SERVER["SERVER_NAME"], $_SERVER["SERVER_NAME"] . " - Newsletter");

                $mail->addCustomHeader( $this->assembleHeader("Return-Path") );
                $mail->addCustomHeader( $this->assembleHeader("Precedence") );
                $mail->addCustomHeader( $this->assembleHeader("List-Id") );
                $mail->addCustomHeader( $this->assembleHeader("List-Unsubscribe") );
                $mail->addCustomHeader( $this->assembleHeader("Errors-To") );

                $mail->addAddress($arg["to"]["email"], $arg["to"]["name"]);

                $mail->addReplyTo("newsletter@" . $_SERVER["SERVER_NAME"]);

                foreach ($arg["cc"] as $recepient) {
                    $mail->addCC($recepient);
                }
                foreach ($arg["bcc"] as $recepient) {
                    $mail->addBCC($recepient);
                }

                if (!empty($this->data["files"])) {
                    foreach ($this->data["files"] as $file) {
                        $mail->addAttachment(dirname(__FILE__)."/tmp/".$file->name, $file->name, "base64", $file->type);
                    }
                }

                if (!empty($this->data["subject"])) {
                    $mail->Subject = $this->data["subject"];
                } else {
                    $mail->Subject = $_SERVER["SERVER_NAME"] . " - Newsletter (" . date("d.F Y") . ")";
                }

                $mail->Body = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $mail->CharSet . "\"><style type=\"text/css\">body { direction: ltr; font-family: Open-Sans, Ubuntu, Verdana, Arial, sans-serif; }</style></head><body>\n";
                $mail->Body .= $this->data["text"];
                $mail->Body .= '</body></html>';

                $mail->AltBody = $mail->html2text(
                    str_replace(
                        array("<hr>", "<br>", "</p>", "</h1>", "</ul>", "</ol>"),
                        array("--------------------------", "\n", "</p>\n\n", "</h1>\n\n", "</ul>\n\n", "</ol>\n\n"),
                        $mail->Body
                    )
                );

                $sent = $mail->send();

                $this->output[] = "Mail has been sent!";

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

//             try {
//                 //Server settings
//                 $mail->SMTPDebug = 2;                                       // Enable verbose debug output
//                 $mail->isSMTP();                                            // Set mailer to use SMTP
//                 $mail->Host       = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
//                 $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
//                 $mail->Username   = 'user@example.com';                     // SMTP username
//                 $mail->Password   = 'secret';                               // SMTP password
//                 $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
//                 $mail->Port       = 587;                                    // TCP port to connect to
//
//                 //Recipients
//                 $mail->setFrom('from@example.com', 'Mailer');
//                 $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
//                 $mail->addAddress('ellen@example.com');               // Name is optional
//                 $mail->addReplyTo('info@example.com', 'Information');
//                 $mail->addCC('cc@example.com');
//                 $mail->addBCC('bcc@example.com');
//
//                 // Attachments
//                 $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//                 $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
//
//                 // Content
//                 $mail->isHTML(true);                                  // Set email format to HTML
//                 $mail->Subject = 'Here is the subject';
//                 $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//                 $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
//
//                 $mail->send();
//                 echo 'Message has been sent';
//             } catch (Exception $e) {
//                 echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//             }

        }

        public function output() {

            $errors = $this->compile_errors();
            if (!empty(trim($errors))) {
                $this->output[] = '<div style="background-color: #e94b4b; color: #000; padding: 30px;">'."<h1>Errors have occurred:</h1>\n".$errors."</div>\n";
            } else {
                $this->output[] = gettext("Newsletter has been sent!");
            }

            return "<p>" . implode("</p>\n<p>", $this->output) . "</p>\n";

        }

        private function validate($type, $var) {

            $type = trim($type);
            if (!is_array($var)) $var = trim($var);

            if ($type == "subject" or $type == "name") {
                $this->add_data($type, $var);
                return true;
            }

            if ($type == "text") {
                if (!empty($var) and is_string($var)) {

                    $var .= file_get_contents(
                        realpath(dirname(__FILE__) . "/../../views/newsletter.footer." . $this->subscriber["locale"] . ".txt")
                    );

                    $search = array(
                        "%name%",
                        "%server%",
                        "%link_unsubscribe%",
                        "%link_manage%",
                        "&amp;",
                        "/admin"
                    );
                    $replace = array(
                        $this->subscriber["name"],
                        $_SERVER["SERVER_NAME"],
                        "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . assembleGetString(
                            "string",
                            array(
                                "view"  => "manage_subscription",
                                "job"   => "unsubscribe",
                                "id"    => $this->subscriber["id"],
                                "hash"  => $this->subscriber["hash"],
                                "agree" => "agree"
                            )
                        ),
                        "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . assembleGetString(
                            "string",
                            array(
                                "view" => "manage_subscription",
                                "id"   => $this->subscriber["id"],
                                "hash" => $this->subscriber["hash"],
                            )
                        ),
                        "&"
                    );

                    $this->add_data($type, str_replace($search, $replace, trim($var)));
                    return true;
                } else {
                    $this->errors["validation"][] = $var . " is not a valid " . $type;
                    return false;
                }
            }

        }

    }

?>
