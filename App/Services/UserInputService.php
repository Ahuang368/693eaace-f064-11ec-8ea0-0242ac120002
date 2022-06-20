<?php

namespace App\Services;

class UserInputService
{
  /**
   * Get user input
   * @param $question
   * @return user input string
   */
  public function getInput($question)
  {
    echo $question;
    return rtrim(fgets(STDIN));
  }
}
