<?php

namespace App\Services\Reports;

require_once __DIR__ . '/ReportService.php';
require_once __DIR__ . '/../ImportDataService.php';
require_once __DIR__ . '/../../Constants.php';

use App\Services\Reports\ReportService;
use App\Services\ImportDataService;
use App\Constants;
use DateTime;

class FeedbackReportService extends ReportService
{
  private $studentsJson;
  private $assessmentsJson;
  private $studentResponsesJson;
  private $questionsJson;

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
   * Generate an array dataset for the Feedback Report
   */
  protected function generateReportData()
  {
    $studentResponses = $this->filterCompletedTestByStudnetId($this->studentResponsesJson);
    $this->sortByPropertyAsc($studentResponses, "completed");
    $latestStudentResponses = $studentResponses[count($studentResponses) - 1];
    
    $studentsList = $this->convertIndexAs($this->studentsJson, 'id');
    $assessmentsList = $this->convertIndexAs($this->assessmentsJson, 'id');
    $questionsList = $this->convertIndexAs($this->questionsJson, 'id');
    $rawScoreList = array_map(fn($response):int => $response['results']['rawScore'], $studentResponses);
   
    $this->reportData['studentName'] = $studentsList[$latestStudentResponses['student']['id']]['firstName'] . " " . $studentsList[$latestStudentResponses['student']['id']]['lastName'];
    $this->reportData['assessmentName'] = $assessmentsList[$latestStudentResponses['assessmentId']]['name'];
    $this->reportData['latestDate'] = DateTime::createFromFormat('d/m/Y H:i:s', $latestStudentResponses['completed']);
    $this->reportData['rawScore'] = $latestStudentResponses['results']['rawScore'];
    $this->reportData['totalResponse'] = count($latestStudentResponses['responses']);

    $this->reportData['strand'] = [];
    foreach ($latestStudentResponses['responses'] as $response) {
      if($response['response'] !== $questionsList[$response['questionId']]['config']['key']) {
        $optionList = $this->convertIndexAs($questionsList[$response['questionId']]['config']['options'], 'id');

        $this->reportData['responses'][$response['questionId']]['stem'] = $questionsList[$response['questionId']]['stem'];
        $this->reportData['responses'][$response['questionId']]['response'] = $optionList[$response['response']];
        $this->reportData['responses'][$response['questionId']]['key'] = $optionList[$questionsList[$response['questionId']]['config']['key']];
        $this->reportData['responses'][$response['questionId']]['hint'] = $questionsList[$response['questionId']]['config']['hint'];
      }
    }
  }

  /**
   * Print the Feedback Report
   */
  protected function printReport()
  {
    echo "\n" . $this->reportData['studentName'] . " recently completed " . $this->reportData['assessmentName'] . " assessment on " . $this->reportData['latestDate']->format('dS F Y h:i A') .
      "\nHe got " . $this->reportData['rawScore'] . " questions right out of " . $this->reportData['totalResponse'] . 
      ". Feedback for wrong answers given below\n\n";

    foreach ($this->reportData['responses'] as $key => $data) {
      echo "Question :" . $data['stem'] . "\nYour answer: " . 
        $data['response']['label'] . " with value " . $data['response']['value'] . 
        "\nRight answer: " . $data['key']['label'] . " with value " . 
        $data['key']['value'] . "\nHint: " . $data['hint'] . "\n";
    }   
  }
}