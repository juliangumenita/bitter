<?php
  namespace Bitter;

  class Controller{

    public $request = [];
    public $success = false;
    public $error = true;
    public $message;
    public $code;
    public $data = null;


    /**
    * Include controller and dispatch the method.
    * @var controller
    * @param request
    * @return Controller
    */
    public static function get(string $controller, $request = []){
      $group = explode("@", $controller);

      $module = $group[0] . "Controller";
      $method = "start";
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
    * Set data.
    * @param key
    * @param set
    * @return void
    */
    public function data($data = null, $key = null, $default = null){
      if(is_null($data)){
        if(is_null($key)){
          return $this->data;
        } elseif (array_key_exists($key, $this->data)){
          return $this->data[$key];
        } return $default;
      } $this->data = $data;
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

    public function value($key, $default = null){
      if(isset($this->request[$key])){
        return $this->request[$key];
      } return $default;
    }

    public function token(){
      $token = Database::fetch(
        Query::build("SELECT `user` FROM `tokens` WHERE `token` = :token;", [
          "token" => $this->val("token")
        ]), "user"
      );

      if(empty($token)){
        $token = 0;
      }
      return $token;
    }

  }
?>
