<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../App/Services/Reports/FeedbackReportService.php';

use PHPUnit\Framework\TestCase;
use App\Services\Reports\FeedbackReportService;

final class FeedbackReportServiceTest extends TestCase
{
  public function testInstance(): void
  {
    $feedbackReportService = new FeedbackReportService();
    $this->assertInstanceOf(FeedbackReportService::class, $feedbackReportService);
  }

  public function testDiagnosticReport(): void
  {
    $this->expectOutputString(
      "\nTony Stark recently completed Numeracy assessment on 16th December 2021 10:46 AM\n" .
      "He got 15 questions right out of 16. Feedback for wrong answers given below\n\n" .
      "Question :What is the 'median' of the following group of numbers 5, 21, 7, 18, 9?\n" .
      "Your answer: A with value 7\n" .
      "Right answer: B with value 9\n" .
      "Hint: You must first arrange the numbers in ascending order. " .
      "The median is the middle term, which in this case is 9\n");
    $feedbackReportService = new FeedbackReportService();
    $feedbackReportService->generate('student1');
  }
}