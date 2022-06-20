<?php

namespace App\Exceptions;

use Exception;

class InvalidPathException extends Exception
{
  public function errorMessage()
  {
    $errorMsg = "Invalid path " . $this->getMessage() .
      "! Please enter the correct path!\n";
    return $errorMsg;
  }
}
