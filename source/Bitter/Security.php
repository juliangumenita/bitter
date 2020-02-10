<?php
  namespace Bitter;

  class Security{
    const METHOD = "AES-256-CBC";
    const KEY = "SECRET_KEY";
    const IV = "16_BYTES_LONG_IV";

    /**
    * @var data: Data to be decrypted
    * @var key: Encryption key
    * @var iv: Initialization vector
    * @return string
    */
    public static function encrypt($data, $key = null, $iv = null): string {
      $key = is_null($key) ? self::KEY : $key;
      $iv = is_null($iv) ? self::IV : $iv;

      if(strlen($iv) !== 16){
        throw new \Exception("IV must be 16 characters long.");
      }

      return base64_encode(
        openssl_encrypt($data, self::METHOD, $key, false, $iv)
      );
    }

    /**
    * @var data: Data to be decrypted
    * @var key: Encryption key
    * @var iv: Initialization vector
    * @return string
    */
    public static function decrypt($data, $key = null, $iv = null): string {
      $key = is_null($key) ? self::KEY : $key;
      $iv = is_null($iv) ? self::IV : $iv;

      if(strlen($iv) !== 16){
        throw new \Exception("IV must be 16 characters long.");
      }

      return openssl_decrypt(
        base64_decode($data), self::METHOD, $key, false, $iv
      );
    }

    /**
    * @var data: Data to be hashed
    * @return string
    */
    public static function hash($data): string {
      return password_hash($data, PASSWORD_DEFAULT);
    }

    /**
    * @var data: Original data
    * @var hash: Hashed data to be verified
    * @return string
    */
    public static function verify($data, $hash):string {
      return password_verify($data, $hash);
    }

  }
?>
