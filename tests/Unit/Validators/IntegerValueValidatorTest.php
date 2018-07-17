<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit\Validators;

use WMDE\FunValidators\Validators\IntegerValueValidator;

/**
 * @covers \WMDE\FunValidators\Validators\IntegerValueValidator
 *
 * @license GNU GPL v2+
 */
class IntegerValueValidatorTest extends \PHPUnit\Framework\TestCase {

	public function testGivenIntegerValues_validationSucceeds(): void {
		$validator = new IntegerValueValidator();
		$this->assertTrue( $validator->validate( '1234567890' )->isSuccessful() );
		$this->assertTrue( $validator->validate( '000123456789' )->isSuccessful() );
	}

	public function testGivenInvalidValues_validationFails(): void {
		$validator = new IntegerValueValidator();
		$this->assertFalse( $validator->validate( '-1234567890' )->isSuccessful() );
		$this->assertFalse( $validator->validate( '21391e213123' )->isSuccessful() );
		$this->assertFalse( $validator->validate( '21391e213123' )->isSuccessful() );
	}
}
