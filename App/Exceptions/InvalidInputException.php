<?php

namespace App\Exceptions;

use Exception;

class InvalidInputException extends Exception
{
  public function errorMessage()
  {
    $errorMsg = "Invalid student ID " . $this->getMessage() .
      "! Please enter the correct Student ID! \n";
    return $errorMsg;
  }
}
