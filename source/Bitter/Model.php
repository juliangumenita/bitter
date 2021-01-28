<?php
  namespace Bitter;

  class Model{
    const STRING = "STRING";
    const INTEGER = "INTEGER";
    const FLOAT = "FLOAT";
    const BOOLEAN = "BOOLEAN";
    const ARRAY = "ARRAY";
    const OBJECT = "OBJECT";

    public $types = [
      "STRING" => [
        "convert" => true,
        "check" => false,
        "function" => "strval"
      ],
      "INTEGER" => [
        "convert" => true,
        "check" => false,
        "function" => "intval"
      ],
      "FLOAT" => [
        "convert" => true,
        "check" => false,
        "function" => "floatval"
      ],
      "BOOLEAN" => [
        "convert" => true,
        "check" => false,
        "function" => "boolval"
      ],
      "ARRAY" => [
        "convert" => false,
        "check" => true,
        "function" => "is_array"
      ],
      "OBJECT" => [
        "convert" => false,
        "check" => true,
        "function" => "is_object"
      ]
    ];

    public $table;
    public $parameters = [];

    public function add($key, $type = null, $nullable = false, $name = null, $description = null){
      $parameters = $this->parameters;

      $parameters[$key] = [
        "type" => $this->types[$type],
        "nullable" => $nullable,
        "name" => $name,
        "description" => $description
      ];

      $this->parameters = $parameters;
    }

    public function set($key, $value = null){
      $parameter = $this->parameters[$key];

      if(!empty($parameter)){
        $function = $parameter["type"]["function"];
        $check = $parameter["type"]["check"];
        $convert = $parameter["type"]["convert"];

        if($convert){
          $value = $function($value);
        }

        if($check){
          if(!$function($value)){
            return false;
          }
        }

        $this->parameters[$key]["value"] = $value;

        return true;
      }

      return false;
    }

    public function data($key,  $value){
      $this->build($key, $value);

      $object = [];

      foreach($this->parameters as $key => $parameter){
        if(!isset($parameter["value"])){
          continue;
        } else {
          $object[$key] = $parameter["value"];
        }
      }

      return $object;
    }

    public static function populate($model, $array, $indicator, $key){
      if(file_exists(__DIR__ . "/../../models/" . $model . "Model.php")){
        require_once __DIR__ . "/../../models/" . $model . "Model.php";

        $objects = [];
        $model = $model . "Model";
        $model = new $model;

        foreach($array as $key => $element){
          $object = $model->data($key, $element[$indicator]);
          if(!empty($object)){
            array_push($objects, $object);
          }
        }

        return $objects;
      }

      return [];
    }

    public static function get($model, $key = null, $value = null){
      if(file_exists(__DIR__ . "/../../models/" . $model . "Model.php")){
        require_once __DIR__ . "/../../models/" . $model . "Model.php";

        $model = $model . "Model";
        $model = new $model;

        $object = $model->data($key, $value);
        if(empty($object)){
          return [];
        }

        return $object;
      }

      return [];
    }

    public function build($key, $value){
      /* Override this function. */
    }

    public function entity($key, $value){
      return self::object($this->table, $key, $value);
    }

    public static function object($table, $key, $value){
      return Database::fetch(
        Query::build("SELECT * FROM @table WHERE @key = :value;", [
          "table" => $table,
          "key" => $key,
          "value" => $value
        ])
      );
    }
  }
?>
