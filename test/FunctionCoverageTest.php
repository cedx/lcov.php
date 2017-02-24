<?php
/**
 * Implementation of the `lcov\test\FunctionCoverageTest` class.
 */
namespace lcov\test;

use Codeception\{Specify};
use lcov\{FunctionCoverage, FunctionData};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \lcov\FunctionCoverage
 */
class FunctionCoverageTest extends TestCase {
  use Specify;

  /**
   * @test ::fromJSON
   */
  public function testFromJSON() {
    $this->specify('should return a null reference with a non-object value', function() {
      $this->assertNull(FunctionCoverage::fromJSON('foo'));
    });

    $this->specify('should return an instance with default values for an empty map', function() {
      $coverage = FunctionCoverage::fromJSON([]);
      $this->assertInstanceOf(FunctionCoverage::class, $coverage);
      $this->assertCount(0, $coverage->getData());
      $this->assertEquals(0, $coverage->getFound());
      $this->assertEquals(0, $coverage->getHit());
    });

    $this->specify('should return an initialized instance for a non-empty map', function() {
      $coverage = FunctionCoverage::fromJSON(['data' => [['lineNumber' => 127]], 'found' => 23, 'hit' => 11]);
      $this->assertInstanceOf(FunctionCoverage::class, $coverage);

      $entries = $coverage->getData();
      $this->assertCount(1, $entries);
      $this->assertInstanceOf(FunctionData::class, $entries[0]);
      $this->assertEquals(127, $entries[0]->getLineNumber());

      $this->assertEquals(23, $coverage->getFound());
      $this->assertEquals(11, $coverage->getHit());
    });
  }

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with default values for a newly created instance', function() {
      $map = (new FunctionCoverage())->jsonSerialize();
      $this->assertCount(3, get_object_vars($map));
      $this->assertCount(0, $map->data);
      $this->assertEquals(0, $map->found);
      $this->assertEquals(0, $map->hit);
    });

    $this->specify('should return a non-empty map for an initialized instance', function() {
      $map = (new FunctionCoverage(23, 11, [new FunctionData()]))->jsonSerialize();
      $this->assertCount(3, get_object_vars($map));
      $this->assertCount(1, $map->data);
      $this->assertInstanceOf(\stdClass::class, $map->data[0]);
      $this->assertObjectHasAttribute('lineNumber', $map->data[0]);
      $this->assertEquals(23, $map->found);
      $this->assertEquals(11, $map->hit);
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $this->specify('should return a format like "FNF:<found>\\n,FNH:<hit>"', function() {
      $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'FNF:0{{eol}}FNH:0'), (string) new FunctionCoverage());

      $coverage = new FunctionCoverage(23, 11, [new FunctionData('main', 127, 3)]);
      $this->assertEquals(str_replace('{{eol}}', PHP_EOL, 'FN:127,main{{eol}}FNDA:3,main{{eol}}FNF:23{{eol}}FNH:11'), (string) $coverage);
    });
  }
}
