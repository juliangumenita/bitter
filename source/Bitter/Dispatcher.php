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
      if(file_exists(__DIR__ . "/../../dispatcher/$dispatcher.php")){
        require __DIR__ . "/../../dispatcher/$dispatcher.php";
      }
    }

  }
?>
