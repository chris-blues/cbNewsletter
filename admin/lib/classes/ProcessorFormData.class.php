<?php

    require_once(dirname(__FILE__) . "/PHPMailer.class.php");

    class Processor_formdata {

        private $type;
        private $validated;
        private $data;
        private $errors;
        private $output;

        public function __construct($subscriber) {

            dump_var($subscriber);
            exit;

            $this->validated = 0;
            $this->data = array();
            $this->errors = array();
            $this->output = array();

            $this->get_post_data();

            if ($this->validated == count($_POST)) {
//                 $errors = count($this->errors, COUNT_RECURSIVE) - count($this->errors);
//                 echo "Validated " . $this->validated . " items from POST array (" . count($_POST) . "). " . $errors . " errors occurred.<br>\n";
                $this->validated = true;
            } else {
                $this->validated = false;
            }

            if ($this->validated) {
                $this->output[] = "Data is valid.";
                $this->email(
                    array(
                        "to"  => array(
                            "email" => $this->data["email"],
                            "name" => ucwords($this->data["name"])
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

            require_once("class_file.php");
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

        private function email($arg) {

            $mail = new PHPMailer(true);
            try {
                $mail->CharSet = "utf-8";
                $mail->setFrom("newsletter@" . $_SERVER["SERVER_NAME"], $_SERVER["SERVER_NAME"] . " - Newsletter");
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

                $mail->isHTML(true);

                if (!empty($this->data["subject"])) {
                    $mail->Subject = $this->data["subject"];
                } else {
                    $mail->Subject = $_SERVER["SERVER_NAME"] . " - Newsletter (" . date("d.F Y") . ")";
                }

                $mail->Body = "<html>\n  <head>\n    <style></style>\n  </head>\n  <body>";
                if (!empty($this->data["subject"])) {
                    $mail->Body .= "    <h1>" . $this->data["subject"] . "</h1>\n\n";
                }

                $mail->Body .= '    <div class="nachricht">' . $this->data["text"] . "</div>\n";

                $mail->Body .= '  </body>'."\n".'</html>'."\n";

                $mail->AltBody = $mail->html2text(str_replace("<hr>", "--------------------------", $mail->Body));

                $mail->send();

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
//                 $this->output[] = '<pre style="font-size: 0.6em;">'.print_r($this, true)."</pre>\n";
            }

            return "<p>" . implode("</p>\n<p>", $this->output) . "</p>\n";

        }

        private function validate($type, $var) {

            $type = trim($type);
            if (!is_array($var)) $var = trim($var);

            if ($type == "email" or $type == "dfgst") {

                if ($type == "dfgst") {
                    if (!empty($var)) {
                        $email = filter_var($var, FILTER_VALIDATE_EMAIL);
                        if ($email !== false) {
                            $this->add_data($type, $email);
                            return true;
                        } else return false;
                    } else return false;
                }
                if ($type == "email") {
                    if (empty($var)) {
                        $this->add_data($type, '');
                        return true;
                    } else return false;
                }

            }

        }

    }

?>
