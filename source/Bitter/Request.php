<?php
  namespace Bitter;

  class Request{
    public $request = [];
    public $started = false;

    public static function start(){
      $this->request = json_decode(file_get_contents("php://input"), true);
    }

    public static function get($key, $default = null){
      if(isset($this->request[$key])){
        return $this->request[$key];
      } return $default;
    }
  }
?>
