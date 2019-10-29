<?php
  namespace Bitter;

  class Validate{

    public $error = true;
    public $success = false;
    public $message;

    public function control($parameters, $options){
      foreach($options as $o => $option){
        if(isset($parameters[$o])){
          $validation = self::validation($o, $parameters[$o], $option);
          if($validation["success"] === false){
            $this->error = true;
            $this->success = false;
            $this->message = $validation["message"];
            return;
          }
        } else {
          $this->error = true;
          $this->success = false;
          $this->message = "Parameter [$o] has not been sent.";
          return;
        }
      }

      $this->error = false;
      $this->success = true;
      $this->message = "All of the data has been successfully checked.";
      return;
    }

    public static function make($parameters, $options){
      $error = false;

      foreach($options as $o => $option){
        if(isset($parameters[$o])){
          if(!self::validation($o, $parameters[$o], $option)){
            $error = true;
          }
        } else {
          $error = true;
        }
      }

      return ($error) ? false : true;
    }

    private static function validation($key, $value, $options){
      foreach ($options as $option) {
        $check = self::option($key, $value, $option);
        if(!$check["success"]){
          return $check;
        }
      } return ["success" => true, "message" => "Data is validated."];
    }

    private static function option($key, $value, $option){
      $exploded = explode(":", $option);
      if(count($exploded) == 1){
        switch ($option) {
          case "string": return ["success" => is_string($value), "message" => "Parameter [$key] must be string."];
          case "number": return ["success" => is_numeric($value), "message" => "Parameter [$key] must be number."];
          case "int": return ["success" => is_int($value), "message" => "Parameter [$key] must be int."];
          case "bool": return ["success" => is_bool($value), "message" => "Parameter [$key] must be bool."];
          case "float": return ["success" => is_float($value), "message" => "Parameter [$key] must be float."];
          case "double": return ["success" => is_double($value), "message" => "Parameter [$key] must be double."];

          case "email": return ["success" => (filter_var($value, FILTER_VALIDATE_EMAIL)), "message" => "Email is not valid."];

          default: return true;
        }
      } else {
        switch ($exploded[0]) {
          case "unique": return (Database::exist(Query::generate("SELECT id FROM @table WHERE @key = :value", [
            "table" => $exploded[1],
            "key" => $key,
            "value" => $value
          ]))) ? ["success" => false, "message" => "Key [$key] has already been used."] : ["success" => true, "message" => "Key [$key] is unique."];

          case "exist": return (Database::exist(Query::generate("SELECT id FROM @table WHERE @key = :value", [
            "table" => $exploded[1],
            "key" => $key,
            "value" => $value
          ]))) ? ["success" => true, "message" => "Key [$key] has already been used."] : ["success" => false, "message" => "Key [$key] has not been registered."];

          case "max": return ["success" => self::max($value, $exploded[1]), "message" => "Parameter [$key] must be maximum {$exploded[1]}."];
          case "min": return ["success" => self::min($value, $exploded[1]), "message" => "Parameter [$key] must be minimum {$exploded[1]}."];

          default: return true;
        }
      }
    }

    /**
    * Check if value is max @param max
    * @param value
    * @param max
    * @return bool
    */
    private static function max($value, $max):bool{
      if(is_numeric($value)){
        return ($value <= $max) ? true : false;
      } return (strlen($value) <= $max) ? true : false;
    }

    /**
    * Check if value is min @param min
    * @param value
    * @param min
    * @return bool
    */
    private static function min($value, $min):bool{
      if(is_numeric($value)){
        return ($value >= $min) ? true : false;
      } return (strlen($value) >= $min) ? true : false;
    }

  }
?>
