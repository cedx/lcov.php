<?php
declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\BranchData` class.
 */
class BranchDataTest extends TestCase {

  /**
   * @test BranchData::fromJson
   */
  public function testFromJson(): void {
    it('should return a null reference with a non-object value', function() {
      expect(BranchData::fromJson('foo'))->to->be->null;
    });

    it('should return an instance with default values for an empty map', function() {
      $data = BranchData::fromJson([]);
      expect($data)->to->be->instanceOf(BranchData::class);
      expect($data->getBlockNumber())->to->equal(0);
      expect($data->getBranchNumber())->to->equal(0);
      expect($data->getLineNumber())->to->equal(0);
      expect($data->getTaken())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $data = BranchData::fromJson(['blockNumber' => 3, 'branchNumber' => 2, 'lineNumber' => 127, 'taken' => 1]);
      expect($data)->to->be->instanceOf(BranchData::class);
      expect($data->getBlockNumber())->to->equal(3);
      expect($data->getBranchNumber())->to->equal(2);
      expect($data->getLineNumber())->to->equal(127);
      expect($data->getTaken())->to->equal(1);
    });
  }

  /**
   * @test BranchData::jsonSerialize
   */
  public function testJsonSerialize(): void {
    it('should return a map with default values for a newly created instance', function() {
      $data = (new BranchData(0, 0, 0))->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(4);
      expect($data->blockNumber)->to->equal(0);
      expect($data->branchNumber)->to->equal(0);
      expect($data->lineNumber)->to->equal(0);
      expect($data->taken)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $data = (new BranchData(127, 3, 2, 1))->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(4);
      expect($data->blockNumber)->to->equal(3);
      expect($data->branchNumber)->to->equal(2);
      expect($data->lineNumber)->to->equal(127);
      expect($data->taken)->to->equal(1);
    });
  }

  /**
   * @test BranchData::__toString
   */
  public function testToString(): void {
    it('should return a format like "BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>"', function() {
      expect((string) new BranchData(0, 0, 0))->to->equal('BRDA:0,0,0,-');
      expect((string) new BranchData(127, 3, 2, 1))->to->equal('BRDA:127,3,2,1');
    });
  }
}
