<?php
declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\Record` class.
 */
class RecordTest extends TestCase {

  /**
   * @test Record::fromJson
   */
  function testFromJson(): void {
    // It should return an instance with default values for an empty map.
    $record = Record::fromJson(new \stdClass);
    assertThat($record, isInstanceOf(Record::class));
    assertThat($record->getBranches(), isNull());
    assertThat($record->getFunctions(), isNull());
    assertThat($record->getLines(), isNull());
    assertThat($record->getSourceFile(), isEmpty());

    // It should return an initialized instance for a non-empty map.
    $record = Record::fromJson((object) [
      'branches' => new \stdClass,
      'functions' => new \stdClass,
      'lines' => new \stdClass,
      'sourceFile' => '/home/cedx/lcov.php'
    ]);

    assertThat($record, isInstanceOf(Record::class));
    assertThat($record->getBranches(), isInstanceOf(BranchCoverage::class));
    assertThat($record->getFunctions(), isInstanceOf(FunctionCoverage::class));
    assertThat($record->getLines(), isInstanceOf(LineCoverage::class));
    assertThat($record->getSourceFile(), equalTo('/home/cedx/lcov.php'));
  }

  /**
   * @test Record::jsonSerialize
   */
  function testJsonSerialize(): void {
    // It should return a map with default values for a newly created instance.
    $map = (new Record(''))->jsonSerialize();
    assertThat(get_object_vars($map), countOf(4));
    assertThat($map->branches, isNull());
    assertThat($map->functions, isNull());
    assertThat($map->lines, isNull());
    assertThat($map->sourceFile, isEmpty());

    // It should return a non-empty map for an initialized instance.
    $map = (new Record('/home/cedx/lcov.php'))
      ->setBranches(new BranchCoverage)
      ->setFunctions(new FunctionCoverage)
      ->setLines(new LineCoverage)
      ->jsonSerialize();

    assertThat(get_object_vars($map), countOf(4));
    assertThat($map->branches, isType('object'));
    assertThat($map->functions, isType('object'));
    assertThat($map->lines, isType('object'));
    assertThat($map->sourceFile, equalTo('/home/cedx/lcov.php'));
  }

  /**
   * @test Record::__toString
   */
  function testToString(): void {
    // It should return a format like "SF:<sourceFile>\\n,end_of_record".
    assertThat((string) new Record(''), equalTo(str_replace('{{eol}}', PHP_EOL, 'SF:{{eol}}end_of_record')));

    $record = (new Record('/home/cedx/lcov.php'))
      ->setBranches(new BranchCoverage)
      ->setFunctions(new FunctionCoverage)
      ->setLines(new LineCoverage);

    $format = "SF:/home/cedx/lcov.php{{eol}}{$record->getFunctions()}{{eol}}{$record->getBranches()}{{eol}}{$record->getLines()}{{eol}}end_of_record";
    assertThat((string) $record, equalTo(str_replace('{{eol}}', PHP_EOL, $format)));
  }
}
