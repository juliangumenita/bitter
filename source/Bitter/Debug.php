<?php
  namespace Bitter;

  class Debug{
    public function __construct(){
      set_error_handler(
        function($error, $message, $file, $line){
          error_reporting(0);

          $lines = file($file);

          $object = [
            "message" => $message,
            "file" => $file,
            "line" => $line,
            "before" => $lines[$line - 2],
            "content" => $lines[$line - 1],
            "after" => $lines[$line],
          ];

          $uuid = Random::uuid();

          file_put_contents("debug/$uuid.json", json_encode($object, JSON_UNESCAPED_UNICODE));

          /* Optionally upload the records to the database. */
          Database::execute(
            Query::insert("debug", [
              "uuid" => $uuid,
              "message" => $message,
              "file" => $file,
              "line" => $line,
              "before" => $lines[$line - 2],
              "content" => $lines[$line - 1],
              "after" => $lines[$line],
            ])
          );
          
        }
      );
    }
  }
?>
