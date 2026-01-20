<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, equalTo};

/**
 * Tests the features of the {@see FunctionData} class.
 */
#[TestDox("FunctionData")]
final class FunctionDataTests extends TestCase {

	#[Test, TestDox("toString()")]
	function testToString(): void {
		// It should return a format like 'FN:<lineNumber>,<functionName>' when used as definition.
		assertThat(new FunctionData()->toString(asDefinition: true), equalTo("FN:0,"));
		assertThat(new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127)->toString(asDefinition: true), equalTo("FN:127,main"));

		// It should return a format like 'FNDA:<executionCount>,<functionName>' when used as data.
		assertThat(new FunctionData()->toString(asDefinition: false), equalTo("FNDA:0,"));
		assertThat(new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127)->toString(asDefinition: false), equalTo("FNDA:3,main"));
	}
}
