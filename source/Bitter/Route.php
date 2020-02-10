<?php
  namespace Bitter;

  class Route{

    public static $started = false;
    public static $url;
    public static $used = false;
    public static $parameters = [];

    /**
    * Init.
    * @return void
    */
    public static function init(){
      if(!self::$started){
        self::$started = true;
        self::start();
      }
    }

    /**
    * Get the key of url variables.
    * @return mixed
    */
    public static function parameter($key){
      self::init();
      $parameters = self::$parameters;
      if(is_array($parameters)){
        if(isset($parameters[$key])){
          return $parameters[$key];
        } return null;
      } return null;
    }

    /**
    * Start the router and include static path.
    * @return void
    */
    public static function start(){
      self::init();
      self::$url = urldecode(
        parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)
      );

      $path = "public" . str_replace(Config::get("slug"), null, self::$url);

      if(file_exists($path) && is_file($path) && self::$url !== "/"){
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $mime = Mime::mime($ext);
        header("Content-type: $mime;");

        echo file_get_contents($path);

        self::$used = true;
        return;
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
      self::init();
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
    * Match the url.
    * @return bool
    */
    public static function match($match){
      self::init();
      $match = Config::get("slug") . $match;
      if(self::$url === $match){
        return true;
      }

      /* We first break down structure. */
      $entities = explode("/", $match);
      $entities = array_filter($entities);

      /* We collect all of the variables we are going to need. */
      $variables = [];

      /* Then we are going to store all of the regex functions here. */
      $fetches = [];

      /* List the variables. */
      foreach($entities as $key => $entity){
        if(!empty(self::between($entity, "{", "}"))){
          $parameter = self::between($entity, "{", "}");
          $variables[$parameter] = $key;
        }
      }

      /* This is a general regex to test if the url matches. */
      $general = null;
      foreach($entities as $e => $entity){
        if(in_array($e, $variables)) {
          $general .= "\/[^#]+";
        } else {
          $general .= "\/$entity";
        }
      }

      preg_match("/$general/", self::$url, $output);

      if(!empty($output)){
        if($output[0] == self::$url){
          return true;
        } return false;
      } return false;
    }

    /**
    * Get the variables.
    * @return bool
    */
    public static function variables($match){
      self::init();
      /* We first break down structure. */
      $entities = explode("/", $match);
      $entities = array_filter($entities);

      /* We collect all of the variables we are going to need. */
      $variables = [];

      /* Then we are going to store all of the regex functions here. */
      $fetches = [];

      /* List the variables. */
      foreach($entities as $key => $entity){
        if(!empty(self::between($entity, "{", "}"))){
          $parameter = self::between($entity, "{", "}");
          $variables[$parameter] = $key;
        }
      }

      /* Create a regexfunction for each variables. */
      foreach($variables as $variable => $v){

        $temp = null;

        foreach($entities as $e => $entity){
          if($e == $v){
            $temp .= "\/([^#]+)";
          } elseif(in_array($e, $variables)) {
            $temp .= "\/[^#]+";
          } else {
            $temp .= "\/$entity";
          }
        }

        $fetches[$variable] = $temp;

      }

      /* We are going to store our get variables here. */
      $parameters = [];

      /* Get variables from url using the variables. */
      foreach($fetches as $f => $fetch){
        $output = [];

        preg_match("/$fetch/", self::$url, $output);

        $parameters[$f] = $output[1];
      }

      self::$parameters = $parameters;
      return $parameters;
    }

    /**
    * Print canvas.
    * @return void
    */
    public static function canvas($match, string $canvas, $dispatchers = []){
      self::init();
      if(self::match($match)){
        if(!self::$used && file_exists(__DIR__ . "/../../canvas/$canvas.php")){
          self::$used = true;
          self::variables($match);
          foreach($dispatchers as $dispatcher) {
            if(file_exists(__DIR__ . "/../../dispatcher/$dispatcher.php")){
              require_once __DIR__ . "/../../dispatcher/$dispatcher.php";
            }
          }
          require_once __DIR__ . "/../../canvas/$canvas.php";
        }
      }
    }

    /**
    * Finish and give 404 page.
    * @return void
    */
    public static function missing($match, string $canvas, $dispatchers = []){
      self::init();
      if(!self::$used){
        if(!self::$used && file_exists(__DIR__ . "/../../canvas/$canvas.php")){
          self::$used = true;
          self::variables($match);
          foreach($dispatchers as $dispatcher) {
            if(file_exists(__DIR__ . "/../../dispatcher/$dispatcher.php")){
              require_once __DIR__ . "/../../dispatcher/$dispatcher.php";
            }
          }
          require_once __DIR__ . "/../../canvas/$canvas.php";
        }
      }
    }

  }
?>
