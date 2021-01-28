<?php
  use Bitter\Response;
  use Bitter\Controller;
  use Bitter\Model;

  class CookieController extends Bitter\Controller{
    public function start(){
      $data = [];

      for ($i=0; $i < 10; $i++) {
        array_push(
          $data,
          Model::get("Cookie")
        );
      }

      return Response::roll(
        true,
        true,
        $data,
        "Cookies are done! Check if some of them are cold or burnt."
      );
    }
  }
?>
