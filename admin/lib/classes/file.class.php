<?php

    class File {

        public $name;
        public $type;
        public $size;
        public $content;

        public function __construct($file) {

            $this->name = $file["name"];
            $this->type = $file["type"];
            $this->size = $file["size"];

            $this->content = $this->convert_content();

        }

        private function convert_content() {

            $tmp = file_get_contents(dirname(__FILE__) . "/tmp/" . $this->name);

        }

    }

?>
