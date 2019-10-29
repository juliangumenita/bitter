<?php
  use Bitter\Route;

  Route::start();
  /* This will catch any file requests and display them correctly. */

  Route::canvas("/", "Example/CanvasExample");
  /* This s an example routing of a canvas. */

  Route::canvas("/dispatcher", "Example/DispatcherExample", [
    "Example/Dispatcher"
  ]);
  /* You can use dispatchers, functions that can be fired before page load. */

  Route::controller("/api", "Example/Cookies@bake");
  /*  You can use the controllers as an api. */
?>
