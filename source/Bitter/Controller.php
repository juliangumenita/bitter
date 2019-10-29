<?php
  namespace Bitter;

  class Controller{

    public $request = [];
    public $success = false;
    public $error = true;
    public $message;
    public $data = null;


    /**
    * Include controller and dispatch the method.
    * @var controller
    * @param request
    * @return Controller
    */
    public static function request(string $controller, $request = []){
      $group = explode("@", $controller);

      $module = $group[0] . "Controller";
      $method = "default";
      if(count($group) > 1){
        $method = $group[1];
      }

      if(empty($request)){
        $request = json_decode(file_get_contents("php://input"), true);
      }

      require_once __DIR__ . "/../../controller/$module.php";
      $dispatcher = new $module;
      $dispatcher->request = $request;
      $dispatcher->$method();
      return $dispatcher;
    }


    /**
    * Get data.
    * @param key
    * @param vanguard
    * @return mixed
    */
    public function data($key = null, $vanguard = null){
      if(isset($key)){
        return $this->data[$key];
      } return $this->data;
    }


    /**
    * Set data.
    * @param key
    * @param set
    * @return void
    */
    public function set($set = []){
      $this->data = $set;
      return;
    }


    /**
    * Set success.
    * @param message
    * @return void
    */
    public function success($message = null){
      $this->success = true;
      $this->error = false;
      $this->message = $message;
      return;
    }


    /**
    * Set error.
    * @param message
    * @return void
    */
    public function error($message = null){
      $this->success = false;
      $this->error = true;
      $this->message = $message;
      return;
    }

    /**
    * Return json data.
    * @return void
    */
    public function json(){
      return json_encode([
        "success" => $this->success,
        "error" => $this->error,
        "message" => $this->message,
        "data" => $this->data
      ], JSON_UNESCAPED_UNICODE);
    }
  }
?>
