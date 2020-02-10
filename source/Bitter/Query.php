<?php
  namespace Bitter;

  class Query{

    public static function build($query, $values){
      foreach($values as $key => $value){
        $query = str_replace(":$key", self::value($value), $query);
        $query = str_replace("@$key", "`$value`", $query);
        $query = str_replace("&$key", trim($value), $query);
      }

      return $query;
    }

    public static function insert($table, $values, $additions = true){
      /*
        Additions
      */
      date_default_timezone_set("Europe/Istanbul");
      $values["uuid"] = isset($values["uuid"]) ? $values["uuid"] : Random::uuid();
      $values["created"] = isset($values["created"]) ? $values["created"] : date("Y-m-d H:i:s");

      /*
        Function
      */
      $query = "INSERT INTO $table (%parameters%) VALUES (%inserts%);";

      $parameters = null;
      $inserts = null;
      foreach($values as $key => $value){
        $parameters .= "`$key`,";
        $inserts .= self::value($value) . ",";
      }

      /* Trim the commas. */
      $parameters = rtrim($parameters, ",");
      $inserts = rtrim($inserts, ",");

      $query = str_replace("%parameters%", $parameters, $query);
      $query = str_replace("%inserts%", $inserts, $query);

      return $query;
    }

    public static function update($table, $values, $key, $identifier = "id"){
      $query = "UPDATE @table SET %sets% WHERE @identifier = :key;";

      $sets = null;
      foreach($values as $key => $value){
        $sets .= "`$key` = " . self::value($value) . ", ";
      }
      $sets = rtrim($sets, ", ");

      $query = str_replace("%sets%", $sets, $query);
      $query = str_replace("@identifier", "`$identifier`", $query);
      $query = str_replace("@table", "`$table`", $query);
      $query = str_replace(":key", self::value($key), $query);

      return $query;
    }

    public static function delete($table, $key, $identifier = "id"){
      $query = "DELETE FROM @table WHERE @identifier = :key;";

      $query = str_replace("@identifier", "`$identifier`", $query);
      $query = str_replace("@table", "`$table`", $query);
      $query = str_replace(":key", self::value($key), $query);

      return $query;
    }

    /**
    * Returns a secure value.
    * @return string
    */
    public static function value($value){
      if(is_numeric($value)){
        return $value;
      } else if(is_null($value)){
        return "NULL";
      } else {
        $value = Database::secure($value);
        return "'$value'";
      }
    }

  }
?>
