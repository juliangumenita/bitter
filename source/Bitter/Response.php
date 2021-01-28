<?php
  namespace Bitter;

  class Response{
    public static $rolled = false;
    public $status = false;
    public $data = [];
    public $message = "";


    public static function roll($response = true, $success = false, $data = [], $message = ""){
      if(!self::$rolled){
        if($response === true){
          self::$rolled = true;
          self::_roll($success, $data, $message);
        } else {
          self::_roll($response->success, $response->data, $response->message);
        }
      }
    }

    private static function _roll($success, $data, $message){
      ignore_user_abort(true);
      set_time_limit(0);
      ob_start();

      echo json_encode([
        "success" => $success,
        "error" => !$success,
        "message" => $message,
        "data" => $data,
      ]);

      $size = ob_get_length();
      header("Content-Encoding: none");
      header("Content-Length: {$size}");
      header("Connection: close");
      ob_end_flush();
      ob_flush();
      flush();
      if(session_id()){
        session_write_close();
      }
    }
  }
?>
