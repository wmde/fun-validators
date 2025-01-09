<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\CanValidateField;
use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

#[CoversClass( CanValidateField::class )]
#[CoversClass( CanValidateFieldTraitStub::class )]
class CanValidateFieldTest extends TestCase {

	public function testGivenValidResult_returnsNoValidations(): void {
		$canValidateFieldTraitStub = new CanValidateFieldTraitStub();

		$validValidationResult = new ValidationResult();

		$this->assertNull(
			$canValidateFieldTraitStub->publicWrapperMethodForGetFieldViolationMethod( $validValidationResult )
		);
	}

	public function testGivenResultWithConstraints_returnsConstraintViolations(): void {
		$canValidateFieldTraitStub = new CanValidateFieldTraitStub();

		$constraintViolation = new ConstraintViolation( "a", "b" );
		$validValidationResult = new ValidationResult( $constraintViolation );

		$this->assertEquals(
			$constraintViolation,
			$canValidateFieldTraitStub->publicWrapperMethodForGetFieldViolationMethod( $validValidationResult )
		);
	}
}
