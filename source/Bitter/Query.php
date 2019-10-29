<?php
  namespace Bitter;

  class Query{

    const ALL = "*";

    /**
    * Generates an easy to use query.
    * @param table
    * @param values
    * @return string
    */
    public static function generate($string, $values){
      foreach ($values as $key => $value) {
        $string = str_replace(":$key", self::value($value), $string);
        $string = str_replace("@$key", "`$value`", $string);
        $string = str_replace("&$key", "$value", $string);
      } return $string;
    }


    /**
    * Builds insert query.
    * @param table
    * @param values
    * @return string
    */
    public static function insert($table, $values){
      $table_values = null;
      $insert_values = null;
      foreach ($values as $key => $value) {
        $value = self::value($value);
        $table_values .= "`$key`, ";
        $insert_values .= "$value, ";
      }
      $table_values = rtrim($table_values, ", ");
      $insert_values = rtrim($insert_values, ", ");
      return "INSERT INTO `$table` ($table_values) VALUES ($insert_values);";
    }


    /**
    * Builds update query using constructors.
    * @param table
    * @param values
    * @param constructors
    * @return string
    */
    public static function update($table, $values, $constructors){
      $set_values = null;
      foreach ($values as $key => $value) {
        $value = self::value($value);
        $set_values .= "`$key` = $value, ";
      }
      $set_values = rtrim($set_values, ", ");
      return "UPDATE `$table` SET $set_values" . self::construct($constructors);
    }


    /**
    * Builds delete query using constructors.
    * @param table
    * @param constructors
    * @return string
    */
    public static function delete($table, $constructors){
      return "DELETE FROM `$table`" . self::construct($constructors);
    }


    /**
    * Builds select query using constructors.
    * @param table
    * @param values
    * @param constructors
    * @return string
    */
    public static function select($table, $values, $constructors = []){
      $select_values = null;
      if(empty($values)){
        $select_values = "*";
      } else if(is_string($values)){
        $select_values = $values;
      } else {
        foreach ($values as $value) {
          if(strpos($value, " ") !== false){
            $select_values .= "$value, ";
          } else {
            $select_values .= "`$value`, ";
          }
        }
      }
      $select_values = rtrim($select_values, ", ");
      return "SELECT $select_values FROM `$table`" . self::construct($constructors);
    }


    /**
    * Builds query using constructors.
    * @param constructors
    * @return string
    */
    private static function construct($constructors = []){
      if(empty($constructors)){
        return ";";
      } else if(is_string($constructors)) {
        return "$constructors;";
      } else {
        $temp = null;
        foreach ($constructors as $constructor) {
          $temp .= $constructor;
        } return "$temp;";
      }
    }


    /**
    * Creates a safe and clean value.
    * For numeric values does not uses apostrophes.
    * For unsafe values uses escape character.
    * @param value
    * @return string
    */
    private static function value($value){
      if(is_numeric($value)){
        return $value;
      } else if(is_null($value)){
        return null;
      } else {
        $value = Database::secure($value);
        $value = trim($value);
        return "'$value'";
      }
    }


    /**
    * Creates a 'where' consturctor.
    * @return string
    */
    public static function where(){
      $selectors = func_get_args();
      if(empty($selectors)){
        return "WHERE 1 = 1";
      } else {
        $temp = "WHERE";
        foreach ($selectors as $selector) {
          $temp .= " $selector, ";
        }
        $temp = rtrim($temp, ", ");
      } return " $temp";
    }


    /**
    * Creates a 'group' consturctor.
    * @param keys
    * @return string
    */
    public static function group($keys){
      if(empty($keys)){
        return null;
      } else {
        $temp = "GROUP BY";
        foreach ($keys as $key) {
          $temp .= " `$key`, ";
        }
        $temp = rtrim($temp, ", ");
      } return " $temp";
    }


    /**
    * Creates a 'having' consturctor.
    * @param selector
    * @return string
    */
    public static function having($selector){
      return " HAVING $selector";
    }


    /**
    * Creates a 'order' consturctor.
    * @param keys
    * @return string
    */
    public static function order($keys){
      if(empty($keys)){
        return null;
      } else {
        $temp = "ORDER";
        foreach ($keys as $key) {
          $temp .= " `$key`, ";
        }
        $temp = rtrim($temp, ", ");
      } return " $temp";
    }


    /**
    * Creates a 'limit' consturctor.
    * @param limit
    * @return string
    */
    public static function limit($limit){
      return " LIMIT $limit";
    }

    /**
    * Creates a 'is' selector.
    * @param key
    * @param value
    * @return string
    */
    public static function is($key, $value){
      $value = self::value($value);
      return "`$key` = $value";
    }


    /**
    * Creates a 'not' selector.
    * @param key
    * @param value
    * @return string
    */
    public static function not($key, $value){
      $value = self::value($value);
      return "`$key` != $value";
    }


    /**
    * Creates a 'bigger' selector.
    * @param key
    * @param value
    * @return string
    */
    public static function bigger($key, $value){
      $value = self::value($value);
      return "`$key` > $value";
    }


    /**
    * Creates a 'smaller' selector.
    * @param key
    * @param value
    * @return string
    */
    public static function smaller($key, $value){
      $value = self::value($value);
      return "`$key` < $value";
    }

  }
?>
