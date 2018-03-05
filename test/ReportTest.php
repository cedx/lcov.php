<?php
declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\Report` class.
 */
class ReportTest extends TestCase {

  /**
   * @test Report::fromCoverage
   */
  public function testFromCoverage(): void {
    $report = Report::fromCoverage(file_get_contents('test/fixtures/lcov.info'));
    $records = $report->getRecords();

    it('should have a test name', function() use ($report) {
      expect($report->getTestName())->to->equal('Example');
    });

    it('should contain three records', function() use ($records) {
      expect($records)->to->have->lengthOf(3);
      expect($records[0])->to->be->instanceOf(Record::class);
      expect($records[0]->getSourceFile())->to->equal('/home/cedx/lcov.php/fixture.php');
      expect($records[1]->getSourceFile())->to->equal('/home/cedx/lcov.php/func1.php');
      expect($records[2]->getSourceFile())->to->equal('/home/cedx/lcov.php/func2.php');
    });

    it('should have detailed branch coverage', function() use ($records) {
      /** @var BranchCoverage $branches */
      $branches = $records[1]->getBranches();
      expect($branches->getFound())->to->equal(4);
      expect($branches->getHit())->to->equal(4);

      $data = $branches->getData();
      expect($data)->to->have->lengthOf(4);
      expect($data[0])->to->be->instanceOf(BranchData::class);
      expect($data[0]->getLineNumber())->to->equal(8);
    });

    it('should have detailed function coverage', function() use ($records) {
      /** @var FunctionCoverage $functions */
      $functions = $records[1]->getFunctions();
      expect($functions->getFound())->to->equal(1);
      expect($functions->getHit())->to->equal(1);

      $data = $functions->getData();
      expect($data)->to->have->lengthOf(1);
      expect($data[0])->to->be->instanceOf(FunctionData::class);
      expect($data[0]->getFunctionName())->to->equal('func1');
    });

    it('should have detailed line coverage', function() use ($records) {
      /** @var LineCoverage $lines */
      $lines = $records[1]->getLines();
      expect($lines->getFound())->to->equal(9);
      expect($lines->getHit())->to->equal(9);

      $data = $lines->getData();
      expect($data)->to->have->lengthOf(9);
      expect($data[0])->to->be->instanceOf(LineData::class);
      expect($data[0]->getChecksum())->to->equal('5kX7OTfHFcjnS98fjeVqNA');
    });

    it('should throw an error if the input is invalid', function() {
      expect(function() { Report::fromCoverage('ZZ'); })->to->throw(LcovException::class);
    });

    it('should throw an error if the report is empty', function() {
      expect(function() { Report::fromCoverage('TN:Example'); })->to->throw(LcovException::class);
    });
  }

  /**
   * @test Report::fromJson
   */
  public function testFromJson(): void {
    it('should return a null reference with a non-object value', function() {
      expect(Report::fromJson('foo'))->to->be->null;
    });

    it('should return an instance with default values for an empty map', function() {
      $report = Report::fromJson([]);
      expect($report)->to->be->instanceOf(Report::class);
      expect($report->getRecords())->to->be->empty;
      expect($report->getTestName())->to->be->empty;
    });

    it('should return an initialized instance for a non-empty map', function() {
      $report = Report::fromJson([
        'records' => [[]],
        'testName' => 'LcovTest'
      ]);

      expect($report)->to->be->instanceOf(Report::class);
      expect($report->getTestName())->to->equal('LcovTest');

      $records = $report->getRecords();
      expect($records)->to->have->lengthOf(1);
      expect($records[0])->to->be->instanceOf(Record::class);
      expect($report->getTestName())->to->equal('LcovTest');
    });
  }

  /**
   * @test Report::jsonSerialize
   */
  public function testJsonSerialize(): void {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new Report)->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(2);
      expect($map->records)->to->be->an('array')->and->be->empty;
      expect($map->testName)->to->be->empty;
    });

    it('should return a non-empty map for an initialized instance', function() {
      $map = (new Report('LcovTest', [new Record('')]))->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(2);
      expect($map->records)->to->be->an('array')->and->have->lengthOf(1);
      expect($map->records[0])->to->be->an('object');
      expect($map->testName)->to->equal('LcovTest');
    });
  }

  /**
   * @test Report::__toString
   */
  public function testToString(): void {
    it('should return a format like "TN:<testName>"', function() {
      expect((string) new Report)->to->be->empty;

      $record = new Record('');
      expect((string) new Report('LcovTest', [$record]))->to->equal(str_replace('{{eol}}', PHP_EOL, "TN:LcovTest{{eol}}$record"));
    });
  }
}
