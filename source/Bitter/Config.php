<?php
  namespace Bitter;

  class Config{

    /**
    * Get config.
    * @return mixed
    */
    public static function get($key){
      if(file_exists(".config")){
        $config = json_decode(
          file_get_contents(".config"), true
        );
        if(array_key_exists($key, $config)){
          return $config[$key];
        } else {
          throw new \Exception("Config not found.");
        } return false;
      } else {
        throw new \Exception("Config file not found.");
      } return false;
    }

  }
?>
