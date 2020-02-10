<?php
  namespace Bitter;

  class Validate{
    
    public static function rules($request, $parameters){
      $optionals = [];

      $request = is_null($request) ? [] : $request;

      /* Find optionals */
      foreach($parameters as $parameter => $rules){
        foreach($rules as $rule){
          if($rule->rule == "optional"){
            array_push($optionals, $parameter);
          }
        }
      }

      /* Validate */
      foreach($parameters as $parameter => $rules){
        if(array_key_exists($parameter, $request)){
          foreach($rules as $rule){
            $rule->value($request[$parameter]);
            if(!self::rule($rule)){
              return new Result(false, $rule->message);
            } continue;
          }
        } else {
          if(in_array($parameter, $optionals)){
            continue;
          } else {
            return new Result(false, "Parameter [$parameter] is missing.");
          }
        }
      } return new Result(true);
    }

    public static function rule(Rule $object){
      $rule = explode(":", $object->rule);
      switch($rule[0]){
        case "string": return self::string($object->value);
        case "numeric": return self::numeric($object->value);
        case "max": return self::max($object->value, intval($rule[1]));
        case "min": return self::min($object->value, intval($rule[1]));
        case "exists": return self::exists($object, $rule[1]);
        case "unique": return self::unique($object, $rule[1]);
      } return "what";
    }

    public static function exists(Rule $rule, $table){
      $identifier = $rule->extension("identifier", "uuid");
      return Database::exists(
        Query::build("SELECT @identifier FROM @table WHERE @identifier = :value;", [
          "identifier" => $identifier,
          "table" => $table,
          "value" => $rule->value()
        ])
      );
    }

    public static function unique(Rule $rule, $table){
      return self::exists($rule, $table) ? false : true;
    }

    public static function string($value){
      return is_string($value);
    }

    public static function numeric($value){
      return is_numeric($value);
    }

    public static function max($value, int $max){
      if(self::string($value)){
        if(strlen($value) <= $max){
          return true;
        } return false;
      } elseif($value <= $max){
        return true;
      } return false;
    }

    public static function min($value, int $min){
      if(self::string($value)){
        if(strlen($value) >= $min){
          return true;
        } return false;
      } elseif($value >= $min){
        return true;
      } return false;
    }

  }
?>
