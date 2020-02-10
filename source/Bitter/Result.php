<?php
  namespace Bitter;

  class Result{
    public $success = false;
    public $error = true;
    public $message = null;
    public $code = null;

    public function __construct(bool $success, string $message = null, $code = null){
      $this->success = $success;
      $this->error = !$success;
      $this->message = $message;
      $this->code = $code;
    }
  }
?>
