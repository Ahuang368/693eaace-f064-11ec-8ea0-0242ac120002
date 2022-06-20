<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../App/Services/Reports/ProgressReportService.php';

use PHPUnit\Framework\TestCase;
use App\Services\Reports\ProgressReportService;

final class ProgressReportServiceTest extends TestCase
{
  public function testInstance(): void
  {
    $progressReportService = new ProgressReportService();
    $this->assertInstanceOf(ProgressReportService::class, $progressReportService);
  }

  public function testDiagnosticReport(): void
  {
    $this->expectOutputString(
      "\nTony Stark has completed Numeracy assessment 3 times in total. " .
      "Date and raw score given below:\n\n" .
      "Date: 16-Dec-2019, Raw Score: 6 out of 16\n" . 
      "Date: 16-Dec-2020, Raw Score: 10 out of 16\n" .
      "Date: 16-Dec-2021, Raw Score: 15 out of 16\n\n" .
      "Tony Stark got 9 more correct in the recent completed assessment than the oldest\n");
    $progressReportService = new ProgressReportService();
    $progressReportService->generate('student1');
  }
}