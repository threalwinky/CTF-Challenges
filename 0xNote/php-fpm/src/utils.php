<?php

require_once 'config.php';

class Color {
    public $str;
    public $color;

    public function __construct($str, $color) {
        $this->str = $str;
        $this->color = $color;
    }

    public function __toString() {
        $escaped_str = htmlspecialchars($this->str, ENT_QUOTES, 'UTF-8');
        $formatted_str = nl2br($escaped_str);
        return "<span style=\"color:$this->color; white-space: pre-wrap;\">$formatted_str</span>";
    }
}

class Black extends Color {
    public $str;
    public $color;

    public function __construct($str) {
        $this->str = $str;
        $this->color = "black";
    }
}   

class Red extends Color {
    public $str;
    public $color;

    public function __construct($str) {
        $this->str = $str;
        $this->color = "red";
    }
}

class Green extends Color {
    public $str;
    public $color;

    public function __construct($str) {
        $this->str = $str;
        $this->color = "green";
    }
}

class Blue extends Color {
    public $str;
    public $color;

    public function __construct($str) {
        $this->str = $str;
        $this->color = "blue";
    }
}

?>