<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../App/Services/Reports/DiagnosticReportService.php';

use PHPUnit\Framework\TestCase;
use App\Services\Reports\DiagnosticReportService;

final class DiagnosticReportServiceTest extends TestCase
{
  public function testInstance(): void
  {
    $diagnosticReportService = new DiagnosticReportService();
    $this->assertInstanceOf(DiagnosticReportService::class, $diagnosticReportService);
  }

  public function testDiagnosticReport(): void
  {
    $this->expectOutputString(
      "\nTony Stark recently completed Numeracy assessment on 16th December 2021 10:46 AM\n" .
      "He got 15 questions right out of 16. Details by strand given below:\n\n" .
      "Number and Algebra: 5 out of 5 correct\n" .
      "Measurement and Geometry: 7 out of 7 correct\n" .
      "Statistics and Probability: 3 out of 4 correct\n");
    $diagnosticReportService = new DiagnosticReportService();
    $diagnosticReportService->generate('student1');
  }
}