<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

#[CoversClass( ValidationResult::class )]
class ValidationResultTest extends TestCase {
	public function testResultWithoutViolationsIsSuccessful(): void {
		$result = new ValidationResult();

		$this->assertTrue( $result->isSuccessful() );
		$this->assertFalse( $result->hasViolations() );
	}

	public function testResultWithViolationsIsNotSuccessful(): void {
		$violation1 = new ConstraintViolation( 'value?', 'test_message_1' );
		$violation2 = new ConstraintViolation( 'value!', 'test_message_2' );
		$result = new ValidationResult( $violation1, $violation2 );

		$this->assertFalse( $result->isSuccessful() );
		$this->assertTrue( $result->hasViolations() );
		$this->assertSame( [ $violation1, $violation2 ], $result->getViolations() );
	}

	public function testSetSourceSetsSourceForAllViolations(): void {
		$violation1 = new ConstraintViolation( 'value?', 'test_message_1' );
		$violation2 = new ConstraintViolation( 'value!', 'test_message_2' );
		$result = new ValidationResult( $violation1, $violation2 );

		$result->setSource( 'a_test_field' );

		$this->assertSame( 'a_test_field', $violation1->getSource() );
		$this->assertSame( 'a_test_field', $violation2->getSource() );
	}

	public function testGetFirstViolationReturnsFirstViolation(): void {
		$violation1 = new ConstraintViolation( 'value?', 'test_message_1' );
		$violation2 = new ConstraintViolation( 'value!', 'test_message_2' );
		$result = new ValidationResult( $violation1, $violation2 );

		$this->assertSame( $violation1, $result->getFirstViolation() );
	}

	public function testGetFirstViolationReturnsNullWhenThereAreNoViolations(): void {
		$result = new ValidationResult();

		$this->assertNull( $result->getFirstViolation() );
	}

}
