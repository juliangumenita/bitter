<?php
  use Bitter\Controller;
  use Bitter\Random;

  class CookiesController extends Controller{

    /* Controller functions must be non-static. */
    public function bake(){
      $heat = Random::number(150, 200);
      if($heat > 180){
        return $this->error("Cookies are burnt.");
      } else {
        $this->set([
          "cookie",
          "cookie",
          "cookie"
        ]); /* You can return any object with set() function. */

        return $this->success("Cookies are baked!");
      }
    }
  }
?>
