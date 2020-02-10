<?php
  namespace Bitter;

  class Rule{
    public $value;

    public $rule;
    public $message;
    public $extensions = [];

    public function __construct($rule, $message = null, $extensions = []){
      $this->rule = $rule;
      $this->message = $message;
      $this->extensions = $extensions;
    }

    public function value($value = null){
      if(is_null($value)){
        if($this->rule == "optional"){
          if(empty($this->value)){
            return $this->extension("default");
          } return $this->value;
        } return $this->value;
      } $this->value = $value;
    }

    public function extension($key = null, $default = null){
      if(is_null($key)){
        return $this->extensions;
      }
      if(array_key_exists($key, $this->extensions)){
        return $this->extensions[$key];
      } return $default;
    }
  }
?>
