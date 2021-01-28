<?php
  namespace Bitter;

  class API{
    public $data;
    public $success;
    public $error;
    public $message;
    public static $used = false;

    public static function parameter($key){
      return Route::parameter($key);
    }

    public static function start(){
      @header("Content-Type: application/json");
      return Route::start();
    }

    /**
    * Finish and give 404 error.
    * @return void
    */
    public static function missing(){
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
      $request = json_decode(file_get_contents("php://input"), true);

      if(Route::match($url) && !Route::$used){
        Route::$used = true;
        Route::variables($url);
        self::$used = true;

        $result = Validate::rules($request, $parameters);
        if($result->error){
          echo json_encode([
            "success" => $result->success,
            "error" => $result->error,
            "message" => $result->message,
            "data" => null,
          ]);
        } else {
          $controller = Controller::get($controller);

          if(!Response::$rolled){
            echo json_encode([
              "success" => $result->success,
              "error" => $result->error,
              "message" => $result->message,
              "data" => null,
            ]);
          }
        }
      }

    }
  }
?>
