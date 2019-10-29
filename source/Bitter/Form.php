<?php
  namespace Bitter;

  class Form{

    /**
    * Starts the session if hasn't already.
    * @return void
    */
    private static function session(){
      if(session_status() == PHP_SESSION_NONE){
        @session_start();
      }
    }

    /**
    * Reloads the page to avoid form resubmission.
    * @return void
    */
    public static function reload(){
      @header("Location: " . $_SERVER["REQUEST_URI"]);
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
    * Check if _FILE key set.
    * @var key
    * @return boolean
    */
    public static function attached($key){
      if(isset($_FILE[$key])){
        return true;
      } return false;
    }

  }
?>
