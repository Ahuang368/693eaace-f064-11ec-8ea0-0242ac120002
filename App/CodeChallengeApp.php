<?php

namespace App;

require_once __DIR__ . '/Exceptions/InvalidInputException.php';
require_once __DIR__ . '/Exceptions/InvalidPathException.php';
require_once __DIR__ . '/Services/UserInputService.php';
require_once __DIR__ . '/Services/Reports/DiagnosticReportService.php';
require_once __DIR__ . '/Services/Reports/ProgressReportService.php';
require_once __DIR__ . '/Services/Reports/FeedbackReportService.php';
require_once __DIR__ . '/Constants.php';

use Exception;
use App\Exceptions\InvalidInputException;
use App\Exceptions\InvalidPathException;
use App\Services\UserInputService;
use App\Services\Reports\DiagnosticReportService;
use App\Services\Reports\ProgressReportService;
use App\Services\Reports\FeedbackReportService;
use App\Constants;

class CodeChallengeApp
{
  public function load()
  {
    $userInputService = new UserInputService();
    echo Constants::$INTRODUCTION_MESSAGE;
    $studentId = $userInputService->getInput(Constants::$STUDENT_ID_QUESTION);
    $reportType = $userInputService->getInput(Constants::$REPORT_TYPE_QUESTION);
    
    // Get user choice
    switch($reportType) {
      case 1:
        $diagnosticReportService = new DiagnosticReportService();
        $diagnosticReportService->generate($studentId);
        break;
      case 2:
        $progressReportService = new ProgressReportService();
        $progressReportService->generate($studentId);
        break;
      case 3:
        $feedbackReportService = new FeedbackReportService();
        $feedbackReportService->generate($studentId);
        break;
      default:
        echo "Invalid Report Type";
    }
  }
}

try {
  $codeChallengeApp = new CodeChallengeApp();
  $codeChallengeApp->load();
} catch (InvalidInputException | InvalidPathException $e) {
  echo $e->errorMessage();
}

