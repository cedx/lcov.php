<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\assertEquals;

/**
 * Tests the features of the {@see LineData} class.
 */
#[TestDox("LineData")]
final class LineDataTests extends TestCase {

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		// It should return a format like 'DA:<lineNumber>,<executionCount>[,<checksum>]'.
		assertEquals("DA:0,0", (string) new LineData);

		$data = new LineData(checksum: "ed076287532e86365e841e92bfc50d8c", executionCount: 3, lineNumber: 127);
		assertEquals("DA:127,3,ed076287532e86365e841e92bfc50d8c", (string) $data);
	}
}
