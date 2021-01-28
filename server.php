<?php
  require "init.php";

  use Bitter\Route;
  use Bitter\API;

  Route::start();
  API::start();

  require __DIR__ . "/app/root.php";
?>
