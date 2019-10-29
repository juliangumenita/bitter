<?php
require "source/Bitter/Config.php";
use Bitter\Config;

spl_autoload_register(
  function($class){
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    $path = $_SERVER["DOCUMENT_ROOT"] . Config::get("slug") . "/source/$class.php";
    if (file_exists($path)) {
      require_once($path);
    }
  }
);
?>
