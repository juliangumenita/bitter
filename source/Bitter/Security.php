<?php
  namespace Bitter;

  class Security{
    const METHOD = "AES-256-CBC";
    const KEY = "SECRET_KEY";
    const IV = "16_BYTES_LONG_IV";

    /**
    * @var data
    * @return string
    */
    public static function encrypt($data): string {
      return base64_encode(
        openssl_encrypt($data, self::METHOD, self::KEY, false, self::IV)
      );
    }

    /**
    * @var data
    * @return string
    */
    public static function decrypt($data): string {
      return openssl_decrypt(
        base64_decode($data), self::METHOD, self::KEY, false, self::IV
      );
    }

    public static function hash($string){
      return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function verify($string, $verify){
      return password_verify($string, $verify);
    }

  }
?>
