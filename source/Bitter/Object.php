<?php
  namespace Bitter;

  class Object{
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
