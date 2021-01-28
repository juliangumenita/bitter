<?php
  use Bitter\Model;
  use Bitter\Random;

  class CookieModel extends Model{
    public function __construct(){
      $this->add("name", self::STRING);
      $this->add("status", self::STRING);
    }

    public function build($key, $value){
      $this->set("name", "Cookie");
      $this->set("status", Random::select("burnt", "cold", "baked"));
    }
  }
?>
