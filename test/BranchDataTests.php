<?php declare(strict_types=1);
namespace Belin\Lcov;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\assertEquals;

/**
 * Tests the features of the {@see BranchData} class.
 */
#[TestDox("BranchData")]
final class BranchDataTests extends TestCase {

	#[Test, TestDox("__toString()")]
	public function testToString(): void {
		// It should return a format like 'BRDA:<lineNumber>,<blockNumber>,<branchNumber>,<taken>'.
		assertEquals("BRDA:0,0,0,-", (string) new BranchData);
		assertEquals("BRDA:127,3,2,1", (string) new BranchData(blockNumber: 3, branchNumber: 2, lineNumber: 127, taken: 1));
	}
}
