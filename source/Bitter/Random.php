<?php
  namespace Bitter;
  class Random{
    const ALL = "all";
    const NUMBER = "number";
    const LOWERCASE = "lowercase";
    const LOWERCASE_NUMBER = "lowercase-number";
    const UPPERCASE = "uppercase";
    const UPPERCASE_NUMBER = "uppercase-number";
    const LOWERCASE_UPPERCASE = "lowercase-uppercase";

    public static function uuid(){
      return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
      );
    }

    public static function number(int $min, int $max, $between = false){
      $min = ($between) ? $min + 1 : $min;
      $max = ($between) ? $max - 1 : $max;
      return rand($min, $max);
    }

    public static function string($lenght = 16, $mode = self::ALL){
      $set = array(
        "lowercase" => "abcdefghijklmnopqrstuvwxyz",
        "uppercase" => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
        "number" => "0123456789"
      );

      $list = array(
        self::ALL => $set["lowercase"] . $set["uppercase"] . $set["number"],
        self::NUMBER => $set["number"],
        self::LOWERCASE => $set["lowercase"],
        self::LOWERCASE_NUMBER => $set["lowercase"] . $set["number"],
        self::UPPERCASE => $set["uppercase"],
        self::UPPERCASE_NUMBER => $set["uppercase"] . $set["number"],
        self::LOWERCASE_UPPERCASE => $set["lowercase"] . $set["uppercase"]
      );

      $charset = str_shuffle($list[$mode]);
      $lenght = strlen($charset);
      $string = null;
      for ($i = 0; $i < $lenght; $i++) {
        $string .= $charset[rand(0, $lenght - 1)];
      }
      return $string;
    }

    public static function select() {
      $count = func_num_args();
      if($count == 0){
        return null;
      }
      $arguments = func_get_args();
      return $arguments[self::number(0, $count - 1)];
    }

  }
?>
