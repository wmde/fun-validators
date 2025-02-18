<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\ConstraintViolation;

#[CoversClass( ConstraintViolation::class )]
class ConstraintViolationTest extends TestCase {
	public function testConstructorSetsProperties(): void {
			$constraintViolation = new ConstraintViolation( "1nval1d", "no_mix_of_numbers_and_letters", "username" );

			$this->assertSame( 'no_mix_of_numbers_and_letters', $constraintViolation->getMessageIdentifier() );
			$this->assertSame( '1nval1d', $constraintViolation->getValue() );
			$this->assertSame( 'username', $constraintViolation->getSource() );
	}

	public function testSetSource(): void {
		$constraintViolation = new ConstraintViolation( "1nval1d", "no_mix_of_numbers_and_letters" );

		$constraintViolation->setSource( 'access_code' );

			$this->assertSame( 'access_code', $constraintViolation->getSource() );
	}

	/**
	 * We'll change this to an exception check when our other libraries don't trigger deprecations any more
	 */
	public function testSetSourceDeprecatesOverridingExistingSource(): void {
		$constraintViolation = new ConstraintViolation( "1nval1d", "no_mix_of_numbers_and_letters", 'username' );
		$hasTriggeredDeprecation = false;
		// @phpstan-ignore-next-line argument.type
		set_error_handler( static function ()use( &$hasTriggeredDeprecation ){
			$hasTriggeredDeprecation = true;
		}, E_USER_DEPRECATED );

		$constraintViolation->setSource( 'access_code' );

		restore_error_handler();
		$this->assertTrue( $hasTriggeredDeprecation );
	}

	/**
	 * We'll change this to an exception check when our other libraries don't trigger deprecations any more
	 */
	public function testSetSourceAllowsOverridingWhenSourceNameMatches(): void {
		$constraintViolation = new ConstraintViolation( "1nval1d", "no_mix_of_numbers_and_letters", 'username' );
		$hasTriggeredDeprecation = false;
		// @phpstan-ignore-next-line argument.type
		set_error_handler( static function ()use( &$hasTriggeredDeprecation ){
			$hasTriggeredDeprecation = true;
		}, E_USER_DEPRECATED );

		$constraintViolation->setSource( 'username' );

		restore_error_handler();
		$this->assertFalse( $hasTriggeredDeprecation );
	}

}
