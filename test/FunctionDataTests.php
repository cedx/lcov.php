<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\assertEquals;

/**
 * Tests the features of the {@see FunctionData} class.
 */
#[TestDox("FunctionData")]
final class FunctionDataTests extends TestCase {

	#[Test, TestDox("toString()")]
	public function testToString(): void {
		// It should return a format like 'FN:<lineNumber>,<functionName>' when used as definition.
		assertEquals("FN:0,", new FunctionData()->toString(asDefinition: true));
		assertEquals("FN:127,main", new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127)->toString(asDefinition: true));

		// It should return a format like 'FNDA:<executionCount>,<functionName>' when used as data.
		assertEquals("FNDA:0,", new FunctionData()->toString(asDefinition: false));
		assertEquals("FNDA:3,main", new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127)->toString(asDefinition: false));
	}
}
