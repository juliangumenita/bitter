<?php
  namespace Bitter;

  class Auth{

    const USER = "user";

    /**
    * Starts the session if hasn't already.
    * @return void
    */
    public static function session(){
      if(session_status() == PHP_SESSION_NONE){
        @session_start();
      }
    }

    /**
    * Return the logged entity.
    * @param type
    * @return int entity
    */
    public static function entity($type = "user"){
      if(self::logged($type)){
        return $_SESSION[$type];
      } return false;
    }

    /**
    * Check if entity has logged in.
    * @param type
    * @return boolean: If the user has logged in.
    */
    public static function logged($type = "user"){
      self::session();
      if(isset($_SESSION[$type])){
        if(!is_null($_SESSION[$type])){
          return true;
        } return false;
      } return false;
    }

    /**
    * Log out the entity.
    * @param type
    * @return true: loggs out the user.
    */
    public static function logout($type = "user"){
      self::session();
      $_SESSION[$type] == null;
      unset($_SESSION[$type]);
      return true;
    }

    /**
    * Log in the entity.
    * @var relation
    * @param type
    * @return true: loggs in the user.
    */
    public static function login($relation, $type = "user"){
      self::session();
      $_SESSION[$type] = $relation;
      return true;
    }

  }
?>
