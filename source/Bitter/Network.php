<?php
  namespace Bitter;

  class Network{

    /**
    * Returns the ip address of client.
    * @return mixed
    */
    public static function ip(){
      if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        return $_SERVER["HTTP_CLIENT_IP"];
      } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
      } else {
        return $_SERVER["REMOTE_ADDR"];
      } return false;
    }

    /**
    * Returns the ip address of client.
    * @return mixed
    */
    public static function header($headers = [], $exit = false){
      foreach($headers as $header => $value){
        @header("$header: $value");
      }

      if($exit){
        exit;
        die();
      }
    }
  }
?>
