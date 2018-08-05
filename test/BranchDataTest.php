<?php
declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\BranchData` class.
 */
class BranchDataTest extends TestCase {

  /**
   * @test BranchData::fromJson
   */
  public function testFromJson(): void {
    // It should return a null reference with a non-object value.
    assertThat(BranchData::fromJson('foo'), isNull());

    // It should return an instance with default values for an empty map.
    $data = BranchData::fromJson([]);
    assertThat($data, isInstanceOf(BranchData::class));
    assertThat($data->getBlockNumber(), equalTo(0));
    assertThat($data->getBranchNumber(), equalTo(0));
    assertThat($data->getLineNumber(), equalTo(0));
    assertThat($data->getTaken(), equalTo(0));

    // It should return an initialized instance for a non-empty map.
    $data = BranchData::fromJson(['blockNumber' => 3, 'branchNumber' => 2, 'lineNumber' => 127, 'taken' => 1]);
    assertThat($data, isInstanceOf(BranchData::class));
    assertThat($data->getBlockNumber(), equalTo(3));
    assertThat($data->getBranchNumber(), equalTo(2));
    assertThat($data->getLineNumber(), equalTo(127));
    assertThat($data->getTaken(), equalTo(1));
  }

  /**
   * @test BranchData::jsonSerialize
   */
  public function testJsonSerialize(): void {
    // It should return a map with default values for a newly created instance.
    $data = (new BranchData(0, 0, 0))->jsonSerialize();
    assertThat(get_object_vars($data), countOf(4));
    assertThat($data->blockNumber, equalTo(0));
    assertThat($data->branchNumber, equalTo(0));
    assertThat($data->lineNumber, equalTo(0));
    assertThat($data->taken, equalTo(0));

    // It should return a non-empty map for an initialized instance.
    $data = (new BranchData(127, 3, 2, 1))->jsonSerialize();
    assertThat(get_object_vars($data), countOf(4));
    assertThat($data->blockNumber, equalTo(3));
    assertThat($data->branchNumber, equalTo(2));
    assertThat($data->lineNumber, equalTo(127));
    assertThat($data->taken, equalTo(1));
  }

  /**
   * @test BranchData::__toString
   */
  public function testToString(): void {
    // It should return a format like "BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>".
    assertThat((string) new BranchData(0, 0, 0), equalTo('BRDA:0,0,0,-'));
    assertThat((string) new BranchData(127, 3, 2, 1), equalTo('BRDA:127,3,2,1'));
  }
}
