<?php
  namespace Bitter;

  class Config{

    const HOST = "localhost";
    /* Database host ip or url. */

    const USERNAME = "root";
    /* MySQL username. */

    const PASSWORD = "";
    /* MySQL password. */

    const DATABASE = "database";
    /* MySQL database name to use. */

    const SLUG = "";
    /*
      If it is under a folder and not root folder.
      Example: /project-name
    */

    public static function get($key){
      if(file_exists(".config")){
        $config = json_decode(
          file_get_contents(".config")
        );
        if(isset($config[$key])){
          return $config[$key];
        } return false;
      } else {
        $upper = mb_strtoupper($key, "UTF-8");
        if(isset(self::$upper)){
          return self::$upper;
        }
      } return false;
    }
  }
?>
