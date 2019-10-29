<?php
  namespace Bitter;

  class Network{

    const LOCATION = "location";

    private static $headers = [
      "location" => "Location"
    ];

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
      $query = null;

      foreach($headers as $header => $value){
        array_key_exists($header, self::$headers){
          header("$header: $value");
        }
      }

      if($exit){
        exit;
        die();
      }
    }
  }
?>
