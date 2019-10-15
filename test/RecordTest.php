<?php declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/** @testdox Lcov\Record */
class RecordTest extends TestCase {

  /** @testdox ::fromJson() */
  function testFromJson(): void {
    it('should return an instance with default values for an empty map', function() {
      $record = Record::fromJson(new \stdClass);
      expect($record->getBranches())->to->be->null;
      expect($record->getFunctions())->to->be->null;
      expect($record->getLines())->to->be->null;
      expect($record->getSourceFile())->to->be->empty;
    });

    it('should return an initialized instance for a non-empty map', function() {
      $record = Record::fromJson((object) [
        'branches' => new \stdClass,
        'functions' => new \stdClass,
        'lines' => new \stdClass,
        'sourceFile' => '/home/cedx/lcov.php'
      ]);

      expect($record->getBranches())->to->not->be->null;
      expect($record->getFunctions())->to->not->be->null;
      expect($record->getLines())->to->not->be->null;
      expect($record->getSourceFile())->to->equal('/home/cedx/lcov.php');
    });
  }

  /** @testdox ->jsonSerialize() */
  function testJsonSerialize(): void {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new Record(''))->jsonSerialize();
      expect(get_object_vars($map))->to->have->lengthOf(4);
      expect($map->branches)->to->be->null;
      expect($map->functions)->to->be->null;
      expect($map->lines)->to->be->null;
      expect($map->sourceFile)->to->be->empty;
    });

    it('should return a non-empty map for an initialized instance', function() {
      $map = (new Record('/home/cedx/lcov.php'))
        ->setBranches(new BranchCoverage)
        ->setFunctions(new FunctionCoverage)
        ->setLines(new LineCoverage)
        ->jsonSerialize();

      expect(get_object_vars($map))->to->have->lengthOf(4);
      expect($map->branches)->to->be->an('object');
      expect($map->functions)->to->be->an('object');
      expect($map->lines)->to->be->an('object');
      expect($map->sourceFile)->to->equal('/home/cedx/lcov.php');
    });
  }

  /** @testdox ->__toString() */
  function testToString(): void {
    it('should return a format like "SF:<sourceFile>\\n,end_of_record"', function() {
      expect((string) new Record(''))->to->equal(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record'));

      $record = (new Record('/home/cedx/lcov.php'))
        ->setBranches(new BranchCoverage)
        ->setFunctions(new FunctionCoverage)
        ->setLines(new LineCoverage);

      $format = "SF:/home/cedx/lcov.php{{eol}}{$record->getFunctions()}{{eol}}{$record->getBranches()}{{eol}}{$record->getLines()}{{eol}}end_of_record";
      expect((string) $record)->to->equal(str_replace('{{eol}}', PHP_EOL, $format));
    });
  }
}
