<?php

namespace App\Services\Reports;

require_once __DIR__ . '/ReportService.php';
require_once __DIR__ . '/../ImportDataService.php';
require_once __DIR__ . '/../../Constants.php';

use App\Services\Reports\ReportService;
use App\Services\ImportDataService;
use App\Constants;
use DateTime;

class ProgressReportService extends ReportService
{
  private $studentsJson;
  private $assessmentsJson;
  private $studentResponsesJson;

  /**
   * Retrieve data from each Json file
   */
  protected function retrieveData()
  {
    $importDataService = new ImportDataService();
    $this->studentsJson = $importDataService->getJsonData(Constants::$STUDENTS_PATH);
    $this->assessmentsJson = $importDataService->getJsonData(Constants::$ASSESSMENTS_PATH);
    $this->studentResponsesJson = $importDataService->getJsonData(Constants::$STUDENT_RESPONSES_PATH);
  }

  /**
   * Generate an array dataset for the Progress Report
   */
  protected function generateReportData()
  {
    $studentResponses = $this->filterCompletedTestByStudnetId($this->studentResponsesJson);
    $this->sortByPropertyAsc($studentResponses, "completed");
  
    $studentsList = $this->convertIndexAs($this->studentsJson, 'id');
    $assessmentsList = $this->convertIndexAs($this->assessmentsJson, 'id');
    $rawScoreList = array_map(fn($response):int => $response['results']['rawScore'], $studentResponses);
    
    $this->reportData['studentName'] = $studentsList[$studentResponses[0]['student']['id']]['firstName'] . " " . $studentsList[$studentResponses[0]['student']['id']]['lastName'];
    $this->reportData['assessmentName'] = $assessmentsList[$studentResponses[0]['assessmentId']]['name'];
    $this->reportData['responses'] = $studentResponses;
    $this->reportData['rawScores'] = $rawScoreList;
  }

  /**
   * Print the Progress Report
   */
  protected function printReport()
  {
    echo "\n" . $this->reportData['studentName'] . " has completed " .
      $this->reportData['assessmentName'] . " assessment " . count($this->reportData['responses']) . 
      " times in total. Date and raw score given below:\n\n";

    foreach ($this->reportData['responses'] as $key => $data) {
      $date = DateTime::createFromFormat('d/m/Y H:i:s', $data['completed']);
      echo "Date: " . $date->format('d-M-Y') . ", Raw Score: " .
        $data['results']['rawScore'] . " out of " . count($data['responses']) . "\n";
    }

    echo "\n" . $this->reportData['studentName'] . " got " .
      max($this->reportData['rawScores']) - min($this->reportData['rawScores']) . 
      " more correct in the recent completed assessment than the oldest\n";
  }
}