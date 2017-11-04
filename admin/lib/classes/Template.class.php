<?php

class Template {

  protected $id;
  protected $name;
  protected $subject;
  protected $text;

  public function getdata() {

    return array(
      "id"      => $this->id,
      "name"    => $this->name,
      "subject" => $this->subject,
      "text"    => $this->text
    );

  }

}

?>
