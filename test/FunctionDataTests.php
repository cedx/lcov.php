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

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		assertEquals("FN:0,\nFNDA:0,", (string) new FunctionData());
		assertEquals("FN:127,main\nFNDA:3,main", (string) new FunctionData(executionCount: 3, functionName: "main", lineNumber: 127));
	}
}
