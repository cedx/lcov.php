<?php
namespace lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `lcov\Record` class.
 */
class RecordTest extends TestCase {

  /**
   * @test Record::fromJSON
   */
  public function testFromJSON() {
    it('should return a null reference with a non-object value', function() {
      expect(Record::fromJSON('foo'))->to->be->null;
    });

    it('should return an instance with default values for an empty map', function() {
      $record = Record::fromJSON([]);
      expect($record)->to->be->instanceOf(Record::class);
      expect($record->getBranches())->to->be->null;
      expect($record->getFunctions())->to->be->null;
      expect($record->getLines())->to->be->null;
      expect($record->getSourceFile())->to->be->empty;
    });

    it('should return an initialized instance for a non-empty map', function() {
      $record = Record::fromJSON([
        'branches' => [],
        'functions' => [],
        'lines' => [],
        'sourceFile' => '/home/cedx/lcov.php'
      ]);

      expect($record)->to->be->instanceOf(Record::class);
      expect($record->getBranches())->to->be->instanceOf(BranchCoverage::class);
      expect($record->getFunctions())->to->be->instanceOf(FunctionCoverage::class);
      expect($record->getLines())->to->be->instanceOf(LineCoverage::class);
      expect($record->getSourceFile())->to->equal('/home/cedx/lcov.php');
    });
  }

  /**
   * @test Record::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with default values for a newly created instance', function() {
      $map = (new Record)->jsonSerialize();
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

  /**
   * @test Record::__toString
   */
  public function testToString() {
    it('should return a format like "SF:<sourceFile>\\n,end_of_record"', function() {
      expect((string) new Record)->to->equal(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record'));

      $record = (new Record('/home/cedx/lcov.php'))
        ->setBranches(new BranchCoverage)
        ->setFunctions(new FunctionCoverage)
        ->setLines(new LineCoverage);

      $format = "SF:/home/cedx/lcov.php{{eol}}{$record->getFunctions()}{{eol}}{$record->getBranches()}{{eol}}{$record->getLines()}{{eol}}end_of_record";
      expect((string) $record)->to->equal(str_replace('{{eol}}', PHP_EOL, $format));
    });
  }
}
