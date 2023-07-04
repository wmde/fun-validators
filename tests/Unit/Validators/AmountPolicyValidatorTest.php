<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit\Validators;

use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\Validators\AmountPolicyValidator;

/**
 * @covers \WMDE\FunValidators\Validators\AmountPolicyValidator
 *
 * @license GPL-2.0-or-later
 * @author Kai Nissen < kai.nissen@wikimedia.de >
 */
class AmountPolicyValidatorTest extends TestCase {

	private const INTERVAL_ONCE = 0;
	private const INTERVAL_MONTHLY = 1;
	private const INTERVAL_QUARTERLY = 3;
	private const INTERVAL_SEMIANNUAL = 6;
	private const INTERVAL_YEARLY = 12;

	/**
	 * @dataProvider smallAmountProvider
	 *
	 * @param float $amount
	 * @param int $interval
	 */
	public function testGivenAmountWithinLimits_validationSucceeds( float $amount, int $interval ): void {
		$this->assertTrue( $this->newAmountValidator()->validate( $amount, $interval )->isSuccessful() );
	}

	public static function smallAmountProvider(): array {
		return [
			[ 750.0, self::INTERVAL_ONCE ],
			[ 20.0, self::INTERVAL_MONTHLY ],
			[ 100.5, self::INTERVAL_QUARTERLY ],
			[ 499.98, self::INTERVAL_SEMIANNUAL ],
			[ 999.99, self::INTERVAL_YEARLY ]
		];
	}

	/**
	 * @dataProvider offLimitAmountProvider
	 *
	 * @param float $amount
	 * @param int $interval
	 */
	public function testGivenAmountTooHigh_validationFails( float $amount, int $interval ): void {
		$this->assertFalse( $this->newAmountValidator()->validate( $amount, $interval )->isSuccessful() );
	}

	public static function offLimitAmountProvider(): array {
		return [
			[ 1750.0, self::INTERVAL_ONCE ],
			[ 101.0, self::INTERVAL_MONTHLY ],
			[ 250.5, self::INTERVAL_QUARTERLY ],
			[ 600, self::INTERVAL_SEMIANNUAL ],
			[ 1337, self::INTERVAL_YEARLY ]
		];
	}

	private function newAmountValidator(): AmountPolicyValidator {
		return new AmountPolicyValidator( 1000, 1000 );
	}

}
