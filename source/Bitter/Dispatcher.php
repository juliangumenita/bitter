<?php
  namespace Bitter;

  class Dispatcher{

    /**
    * Include controller and dispatch the method.
    * @var dispatcher
    * @param request
    * @return Dispatcher
    */
    public static function run(string $dispatcher, $request = []){
      if(file_exists("dispatcher/$dispatcher.php")){
        require "dispatcher/$dispatcher.php";
      }
    }

  }
?>
