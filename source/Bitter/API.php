<?php
  namespace Bitter;

  class API{
    public static $started = false;
    public $data;
    public $success;
    public $error;
    public $message;

    /**
    * Init.
    * @return void
    */
    private static function init(){
      if(!self::$started){
        self::$started = true;
        self::start();
      }
    }

    private static function parameter($key){
      self::init();
      return Route::parameter($key);
    }

    private static function start(){
      self::init();
      header("Content-Type: application/json");
      return Route::start();
    }

    /**
    * Finish and give 404 error.
    * @return void
    */
    public static function missing(){
      self::init();
      if(!self::$used){
        print(json_encode([
          "success" => false,
          "error" => true,
          "message" => "Incorrect route has been called.",
          "data" => null
        ], JSON_UNESCAPED_UNICODE));
      }
    }

    public static function post($url, $controller, $parameters = []){
      self::init();
      $request = json_decode(file_get_contents("php://input"), true);

      if(Route::match($url)){
        Route::$used = true;

        $result = Validate::rules($request, $parameters);
        if($result->error){
          echo json_encode([
            "success" => $result->success,
            "error" => $result->error,
            "message" => $result->message,
            "data" => null,
          ]);
        } else {
          $controller = Controller::request($controller);
          echo json_encode([
            "success" => $controller->success,
            "error" => $controller->error,
            "message" => $controller->message,
            "data" => $controller->data,
          ]);
        }
      }

    }
  }
?>
