<?php

namespace App\Services\Reports;

require_once __DIR__ . '/ReportService.php';
require_once __DIR__ . '/../ImportDataService.php';
require_once __DIR__ . '/../../Constants.php';

use App\Services\Reports\ReportService;
use App\Services\ImportDataService;
use App\Constants;
use DateTime;

class DiagnosticReportService extends ReportService
{
  private $studentsJson;
  private $assessmentsJson;
  private $studentResponsesJson;
  private $questionsJson;
  private $reportData;

  /**
   * Retrieve data from each Json file
   */
  protected function retrieveData()
  {
    $importDataService = new ImportDataService();
    $this->studentsJson = $importDataService->getJsonData(Constants::$STUDENTS_PATH);
    $this->assessmentsJson = $importDataService->getJsonData(Constants::$ASSESSMENTS_PATH);
    $this->studentResponsesJson = $importDataService->getJsonData(Constants::$STUDENT_RESPONSES_PATH);
    $this->questionsJson = $importDataService->getJsonData(Constants::$QUESTIONS_PATH);
  }

  /**
   * Generate an array dataset for the Diagnostic Report
   */
  protected function generateReportData()
  {
    $studentResponses = $this->filterCompletedTestByStudnetId($this->studentResponsesJson);
    $this->sortByPropertyAsc($studentResponses, "completed");
    $latestStudentResponses = $studentResponses[count($studentResponses) - 1];

    $studentsList = $this->convertIndexAs($this->studentsJson, 'id');
    $assessmentsList = $this->convertIndexAs($this->assessmentsJson, 'id');
    $questionsList = $this->convertIndexAs($this->questionsJson, 'id');
    
    $this->reportData['studentName'] = $studentsList[$latestStudentResponses['student']['id']]['firstName'] . " " . $studentsList[$latestStudentResponses['student']['id']]['lastName'];
    $this->reportData['assessmentName'] = $assessmentsList[$latestStudentResponses['assessmentId']]['name'];
    $this->reportData['latestDate'] = DateTime::createFromFormat('d/m/Y H:i:s', $latestStudentResponses['completed']);
    $this->reportData['rawScore'] = $latestStudentResponses['results']['rawScore'];
    $this->reportData['totalResponse'] = count($latestStudentResponses['responses']);

    $this->reportData['strand'] = [];
    foreach ($latestStudentResponses['responses'] as $response) {

      $strand = $questionsList[$response['questionId']]['strand'];
      $score = $response['response'] == $questionsList[$response['questionId']]['config']['key'] ? 1 : 0;

      if (!array_key_exists($strand, $this->reportData['strand'])) {
        $this->reportData['strand'][$strand][0] = 0;
        $this->reportData['strand'][$strand][1] = 0;
      }

      $this->reportData['strand'][$strand][$score]++;
    }
  }

  /**
   * Print the Diagnostic Report
   */
  protected function printReport()
  {
    echo "\n" . $this->reportData['studentName'] . " recently completed " . $this->reportData['assessmentName'] . " assessment on " .
      $this->reportData['latestDate']->format('dS F Y h:i A') .
      "\nHe got " . $this->reportData['rawScore'] . " questions right out of " .
      $this->reportData['totalResponse'] . ". Details by strand given below:\n\n";

    foreach ($this->reportData['strand'] as $key => $data) {
      echo $key . ": " . $data[1] . " out of " . $data[0] + $data[1] . " correct\n";
    }
  }
}
