<?php

namespace App\Services\Reports;

require_once __DIR__ . '/../../Exceptions/InvalidInputException.php';

use APP\Exceptions\InvalidInputException;

abstract class ReportService
{
  protected $studentId;

  abstract protected function retrieveData();

  abstract protected function generateReportData();

  abstract protected function printReport();

  /**
   * filter an array includes 'completed' key
   * @param $arrayData
   * @return array with 'completed' key 
   */
  protected function filterCompletedTestByStudnetId($arrayData)
  {
    $sortedData = array_filter($arrayData, fn ($response) => $response['student']['id'] == $this->studentId && array_key_exists('completed', $response));
    
    /**
     * validate student ID input, 
     * throw to exception when input is invalid
     */
    if (count($sortedData) == 0) {
      throw new InvalidInputException($this->studentId);
    }

    return $sortedData;
  }

  /**
   * Sort Array by the property in ascending order
   * @param $arrayData
   * @param $property
   */
  protected function sortByPropertyAsc($arrayData, $property) {
    usort($arrayData, fn ($a, $b) => strcmp($a[$property], $b[$property]));
  }

  /**
   * Change an array index to the property of its own object
   * @param $arrayData
   * @param $property
   * @return array
   */
  protected function convertIndexAs($arrayData, $property) {
    $newArrayData = [];
    foreach ($arrayData as $data) {
      $newArrayData[$data[$property]] = $data;
    }
    return $newArrayData;
  }

  /**
   * Generate report by Student ID
   * @param $studentId
   */
  public function generate($studentId)
  {
    $this->studentId = $studentId;
    $this->retrieveData();
    $this->generateReportData();
    $this->printReport();
  }
}