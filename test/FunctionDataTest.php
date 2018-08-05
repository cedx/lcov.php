<?php
declare(strict_types=1);
namespace Lcov;

use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `Lcov\FunctionData` class.
 */
class FunctionDataTest extends TestCase {

  /**
   * @test FunctionData::fromJson
   */
  public function testFromJson(): void {
    // It should return a null reference with a non-object value.
      assertThat(FunctionData::fromJson('foo'), isNull());

    // It should return an instance with default values for an empty map.
      $data = FunctionData::fromJson([]);
      assertThat($data, isInstanceOf(FunctionData::class));
      assertThat($data->getExecutionCount(), equalTo(0));
      assertThat($data->getFunctionName(), isEmpty());
      assertThat($data->getLineNumber(), equalTo(0));

    // It should return an initialized instance for a non-empty map.
      $data = FunctionData::fromJson(['executionCount' => 3, 'functionName' => 'main', 'lineNumber' => 127]);
      assertThat($data, isInstanceOf(FunctionData::class));
      assertThat($data->getExecutionCount(), equalTo(3));
      assertThat($data->getFunctionName(), equalTo('main'));
      assertThat($data->getLineNumber(), equalTo(127));
  }

  /**
   * @test FunctionData::jsonSerialize
   */
  public function testJsonSerialize(): void {
    // It should return a map with default values for a newly created instance.
      $data = (new FunctionData('', 0))->jsonSerialize();
      assertThat(get_object_vars($data), countOf(3));
      assertThat($data->executionCount, equalTo(0));
      assertThat($data->functionName, isEmpty());
      assertThat($data->lineNumber, equalTo(0));

    // It should return a non-empty map for an initialized instance.
      $data = (new FunctionData('main', 127, 3))->jsonSerialize();
      assertThat(get_object_vars($data), countOf(3));
      assertThat($data->executionCount, equalTo(3));
      assertThat($data->functionName, equalTo('main'));
      assertThat($data->lineNumber, equalTo(127));
  }

  /**
   * @test FunctionData::__toString
   */
  public function testToString(): void {
    // It should return a format like "FN:<lineNumber>,<functionName>" when used as definition.
    $data = new FunctionData('', 0);
    assertThat($data->toString(false), equalTo('FNDA:0,'));
    assertThat($data->toString(true), equalTo('FN:0,'));

    // It should return a format like "FNDA:<executionCount>,<functionName>" when used as data.
    $data = new FunctionData('main', 127, 3);
    assertThat($data->toString(false), equalTo('FNDA:3,main'));
    assertThat($data->toString(true), equalTo('FN:127,main'));
  }
}
