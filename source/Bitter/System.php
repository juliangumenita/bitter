<?php
  class System{

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

    /**
    * Get _GET key.
    * @var key
    * @param vanguard = [null]: returns selected parameter if key does not exists.
    * @return _GET[key]
    */
    public static function get($key, $vanguard = null){
      if(isset($_GET[$key])){
        return $_GET[$key];
      } return $vanguard;
    }

    /**
    * Check if _GET key set.
    * @var key
    * @return boolean: return true if the _GET key is set.
    */
    public static function got($key){
      if(isset($_GET[$key])){
        return true;
      } return false;
    }

    /**
    * Get _POST key.
    * @var key
    * @param vanguard = [null]: returns selected parameter if key does not exists.
    * @return _POST[key]
    */
    public static function post($key, $vanguard = null){
      if(isset($_POST[$key])){
        return $_POST[$key];
      } return $vanguard;
    }

    /**
    * Check if _POST key set.
    * @var set
    * @return boolean
    */
    public static function posted($key){
      return isset($_POST[$key]);
    }

    /**
    * Get _FILE key.
    * @var key
    * @param vanguard = [null]: returns selected parameter if key does not exists.
    * @return _FILE[key]: return true if the _POST key is set.
    */
    public static function file($key, $vanguard = null){
      if(isset($_FILE[$key])){
        return $_FILE[$key];
      } return $vanguard;
    }

    /**
    * Changes header to change location.
    * @var location
    * @param global
    */
    public static function location($location){
      return @header("Location: $location");
    }

  }
?>
