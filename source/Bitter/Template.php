<?php
  namespace Bitter;

  use Bitter\Plugin\Setting;

  class Template{

    private static $options = [
      "state" => "default",
      "parameter" => "default",
      "dir" => "template/",
      "ext" => "html"
    ];

    /**
    * Return full options.
    * @param options
    * @return array
    */
    private static function options($options):array{
      if(is_array($options)){
        return [
          "state" =>
            (array_key_exists("state", $options))
            ? $options["state"]
            : self::$options["state"],
          "parameter" =>
            (array_key_exists("parameter", $options))
            ? $options["parameter"]
            : self::$options["parameter"],
          "dir" =>
            (array_key_exists("dir", $options))
            ? $options["dir"]
            : self::$options["dir"],
          "ext" =>
            (array_key_exists("ext", $options))
            ? $options["ext"]
            : self::$options["ext"]
        ];
      } else {
        return [
          "state" => $options,
          "parameter" => self::$options["parameter"],
          "dir" => self::$options["dir"],
          "ext" => self::$options["ext"]
        ];
      }
    }

    /**
    * Get string in a string between to strings.
    * @param string
    * @param start
    * @param end
    * @param multiple: false
    * @return array
    */
    private static function between($string, $start, $end, $multiple = false){
      $result = [];
      foreach (explode($start, $string) as $key => $value) {
        if(strpos($value, $end) !== false){
          $result[] = substr($value, 0, strpos($value, $end));
        }
      } if(empty($result)){
        return [];
      } return ($multiple) ? $result : $result[0];
    }

    /**
    * Find version from string.
    * @param string
    * @return string
    */
    private static function version($string):string{
      $exploded = explode("@", $string);
      if(count($exploded) == 1){
        return self::$options["state"];
      } return end($exploded);
    }


    /**
    * Find version from string.
    * @param string
    * @return string
    */
    private static function template($string):string{
      $exploded = explode("@", $string);
      return $exploded[0];
    }


    /**
    * Find parameters for a template.
    * @param template
    * @param parameters
    * @return string
    */
    private static function parameters($template, $parameters){
      if(isset($parameters[":$template"])){
        return $parameters[":$template"];
      } return [];
    }


    /**
    * Replace all of the parameters.
    * @param component
    * @param parameters
    * @return string
    */
    private static function replace($component, $parameters){
      foreach ($parameters as $key => $param) {
        if(!is_array($param)){
          $component = str_replace("{{{$key}}}", $param, $component);
        }
      } return $component;
    }


    /**
    * Get the component out of the file.
    * @param component
    * @param parameters
    * @return string
    */
    private static function component($file, $options){
      $component = self::between($file, "@{$options["state"]}", "{$options["state"]}@");
      if(empty($component)){
        $state = self::$options["state"]; /*Get the default state. */
        $component = self::between($file, "@$state", "$state@");
        /* If there is no state as such, get the default state. */
        if(empty($component)){
          return;
          /* If component still empty, that means there is an error with syntax. */
        }
      } return $component;
    }


    /**
    * Get a template.
    * @param name
    * @param parameters
    * @param options
    * @return string
    */
    public static function get($name, $parameters = [], $options = "default"){
      $options = self::options($options);

      if(is_string($parameters)){
        $parameters = [$options["parameter"] => $parameters];
        /*
        * If parameter is not an array, only one
        * parameter will be passed and that is {{default}}
        */
      } else if(!is_string($parameters)){
        $parameters["random"] = Random::string(32);
        // DEBUG: This is an addition to the library.
      }

      $file = @file_get_contents($options["dir"] . $name . "." . $options["ext"]);
      /* Getting the contents. */

      $component = self::component($file, $options);
      $component = self::replace($component, $parameters);

      $templates = self::between($component, "[[", "]]", true);
      foreach ($templates as $template) {
        $component = str_replace("[[{$template}]]", self::get(
          self::template($template), self::parameters(self::template($template), $parameters), self::version($template)
        ), $component);
      }
      /* Replace all of the inner templates. */

      $globals = self::between($component, "{global:", "}", true);
      foreach ($globals as $global) {
        $component = str_replace("{global:{$global}}",
          (isset($GLOBALS[$global])) ? $GLOBALS[$global] : null
        , $component);
        // DEBUG: This is an addition to the library.
      }
      /* Replace all of the inner templates with global values. */

      $component = preg_replace("/{{[\s\S]+?}}/", null, $component);
      $component = preg_replace("/\[\[[\s\S]+?\]\]/", null, $component);
      /* Cleaning all unused parameters. */
      return $component;
    }

    /**
    * Prints a template.
    */
    public static function print($name, $parameters = [], $options = "default"){
      echo self::get($name, $parameters, $options);
    }

    /**
    * Returns multiple templates from an array.
    */
    public static function multiple($name, $parameters = [], $options = "default"){
      $temp = null;
      foreach ($group as $parameters) {
        $temp .= self::get($name, $parameters, $options);
      } return $temp;
    }

  }
?>
