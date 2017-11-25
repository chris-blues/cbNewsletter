<?php

  class NLarchive {

    protected $id;
    protected $date;
    protected $subject;
    protected $text;

    // =============  methods  =============

    public function getdata() {

      return array(

        "id"      => $this->id,
        "date"    => $this->date,
        "subject" => $this->subject,
        "text"    => $this->text,

      );

    }

  }

?>
