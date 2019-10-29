<?php
  namespace Bitter;

  class Schema {
    const ID = "id";

    const KEY = "NOT NULL AUTO_INCREMENT";
    const STAMP = "DEFAULT CURRENT_TIMESTAMP";
    const NULL = "DEFAULT NULL";

    const RESTRICT = "RESTRICT";
    const CASCADE = "CASCADE";
    const SET_NULL = "SET NULL";
    const NO_ACTION = "NO ACTION";

    public static function database() {
      $args = func_get_args();
      $string = null;
      foreach ($args as $arg) {
        $string .= $arg . "\n\n";
      }
      return $string;
    }

    public static function table($slug, $args, $primary = "id", $relations = []) {
      $columns = null;
      foreach ($args as $arg) {
        $columns .= $arg . ",\n";
      }
      $string = "DROP TABLE IF EXISTS `$slug`;\n";
      $string .= "CREATE TABLE IF NOT EXISTS `$slug` (\n{$columns}PRIMARY KEY (`$primary`)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

      foreach($relations as $relation){
        $string .= "\nALTER TABLE `$slug` ADD FOREIGN KEY (`{$relation['column']}`) REFERENCES `{$relation['table']}`(`{$relation['key']}`) ON DELETE {$relation['delete']} ON UPDATE {$relation['update']};";
      }
      return $string;
    }

    public static function column($slug, $type, $lenght, $option = "NOT NULL") {
      if(is_null($lenght)){
        return "`$slug` $type $option";
      } return "`$slug` $type($lenght) $option";
    }

    /**
    * @var value: a string value.
    */
    public static function default($value) {
      if(is_numeric($value)){
        return "DEFAULT $value";
      } return "DEFAULT '$value'";
    }

    public static function relation($column, $table, $key, $delete = "RESTRICT", $update = "NO ACTION"){
      return [
        "column" => $column,
        "table" => $table,
        "key" => $key,
        "delete" => $delete,
        "update" => $update
      ];
    }

  }
?>
