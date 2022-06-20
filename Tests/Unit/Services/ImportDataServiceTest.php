<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

require_once __DIR__ . '/../../../App/Services/ImportDataService.php';


use PHPUnit\Framework\TestCase;
use App\Services\ImportDataService;
use App\Exceptions\InvalidPathException;

final class ImportDataServiceTest extends TestCase
{
  public function testGetJsonDataWithValidPath(): void
  {
    $validPath = '/../../Data/assessments.json';
    $expectedJson = '[{"id": "assessment1", "name": "Numeracy", "questions": [{"questionId": "numeracy1", "position": 1}]}]';
    $importDataService = new ImportDataService();
    $data = $importDataService->getJsonData($validPath);
    $this->assertEquals(json_decode($expectedJson, true), $data);
  }

  public function testGetJsonDataWithInvalidPath(): void
  {
    $this->expectException(InvalidPathException::class);
    $invalidPath = '/invalidPath/assessments.json';
    $importDataService = new ImportDataService();
    $data = $importDataService->getJsonData($invalidPath);
  }
}