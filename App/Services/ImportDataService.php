<?php

namespace App\Services;

require_once __DIR__ . '/../Exceptions/InvalidPathException.php';

use APP\Exceptions\InvalidPathException;
use \Exception;

class ImportDataService
{
  /**
   * Retrieve Json data by file path
   * @param $path
   * @return Json data
   */
  public function getJsonData($path)
  {
    try {
      $jsonData = json_decode(file_get_contents(__DIR__ . $path), true);
    } catch (Exception $e) {
      throw new InvalidPathException($path);
    }
    return $jsonData;
  }
}
