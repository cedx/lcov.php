<?php declare(strict_types=1);
namespace Lcov;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/** @testdox Lcov\FunctionData */
class FunctionDataTest extends TestCase {

  /** @testdox ::fromJson() */
  function testFromJson(): void {
    it('should return an instance with default values for an empty map', function() {
      $data = FunctionData::fromJson(new \stdClass);
      expect($data->getExecutionCount())->to->equal(0);
      expect($data->getFunctionName())->to->be->empty;
      expect($data->getLineNumber())->to->equal(0);
    });

    it('should return an initialized instance for a non-empty map', function() {
      $data = FunctionData::fromJson((object) ['executionCount' => 3, 'functionName' => 'main', 'lineNumber' => 127]);
      expect($data->getExecutionCount())->to->equal(3);
      expect($data->getFunctionName())->to->equal('main');
      expect($data->getLineNumber())->to->equal(127);
    });
  }

  /** @testdox ->jsonSerialize() */
  function testJsonSerialize(): void {
    it('should return a map with default values for a newly created instance', function() {
      $data = (new FunctionData('', 0))->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(3);
      expect($data->executionCount)->to->equal(0);
      expect($data->functionName)->to->be->empty;
      expect($data->lineNumber)->to->equal(0);
    });

    it('should return a non-empty map for an initialized instance', function() {
      $data = (new FunctionData('main', 127, 3))->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(3);
      expect($data->executionCount)->to->equal(3);
      expect($data->functionName)->to->equal('main');
      expect($data->lineNumber)->to->equal(127);
    });
  }

  /** @testdox ->__toString() */
  function testToString(): void {
    it('should return a format like "FN:<lineNumber>,<functionName>" when used as definition', function() {
      $data = new FunctionData('', 0);
      expect($data->toString(false))->to->equal('FNDA:0,');
      expect($data->toString(true))->to->equal('FN:0,');
    });

    it('should return a format like "FNDA:<executionCount>,<functionName>" when used as data', function() {
      $data = new FunctionData('main', 127, 3);
      expect($data->toString(false))->to->equal('FNDA:3,main');
      expect($data->toString(true))->to->equal('FN:127,main');
    });
  }
}
