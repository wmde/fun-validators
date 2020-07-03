<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit\Validators;

use WMDE\FunValidators\Validators\AddressValidator;

/**
 * @covers \WMDE\FunValidators\Validators\AddressValidator
 */
class AddressValidatorTest extends \PHPUnit\Framework\TestCase {

	private const COUNTRY_POSTCODE_PATTERNS = [
		'DE' => '/^[0-9]{5}$/',
		'AT' => '/^[0-9]{4}$/',
		'CH' => '/^[0-9]{4}$/',
		'BE' => '/^[0-9]{4}$/',
		'IT' => '/^[0-9]{5}$/',
		'LI' => '/^[0-9]{4}$/',
		'LU' => '/^[0-9]{4}$/',
	];

	private const ADDRESS_PATTERNS = [
		'firstName' => "/^[A-Za-z\u00C0-\u00D6\u00D8-\u00f6\u00f8-\u00ff\\s\\-\\.\\']+$/",
		'lastName' => "/^[A-Za-z\u00C0-\u00D6\u00D8-\u00f6\u00f8-\u00ff\\s\\-\\.\\']+$/",
		'postcode' => '/^.+$/',
	];

	public function testGivenValidPostalAddress_noViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePostalAddress( 'Test 1234', '12345', 'Test City', 'Germany' );
		$this->assertTrue( $validationResult->isSuccessful() );
	}

	public function testGivenValidPersonName_noViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePersonName( 'Herr', 'Prof. Dr.', 'Tester', 'Testing' );
		$this->assertTrue( $validationResult->isSuccessful() );
	}

	public function testGivenValidCompany_noViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validateCompanyName( 'Test Company GmbH & Co. KG' );
		$this->assertTrue( $validationResult->isSuccessful() );
	}

	public function testGivenTooLongPostalValues_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePostalAddress(
			str_repeat( 'a', 101 ),
			str_repeat( '1', 17 ),
			str_repeat( 'a', 101 ),
			str_repeat( 'a', 9 )
		);
		$this->assertFalse( $validationResult->isSuccessful() );
		$this->assertCount( 4, $validationResult->getViolations() );
		$this->assertSame( 'street', $validationResult->getViolations()[0]->getSource() );
		$this->assertSame( 'postcode', $validationResult->getViolations()[1]->getSource() );
		$this->assertSame( 'city', $validationResult->getViolations()[2]->getSource() );
		$this->assertSame( 'country', $validationResult->getViolations()[3]->getSource() );
	}

	public function testGivenTooLongNameValues_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePersonName(
			str_repeat( 'a', 17 ),
			str_repeat( 'a', 17 ),
			str_repeat( 'a', 51 ),
			str_repeat( 'a', 51 )
		);
		$this->assertFalse( $validationResult->isSuccessful() );
		$this->assertCount( 4, $validationResult->getViolations() );
		$this->assertSame( 'salutation', $validationResult->getViolations()[0]->getSource() );
		$this->assertSame( 'title', $validationResult->getViolations()[1]->getSource() );
		$this->assertSame( 'firstName', $validationResult->getViolations()[2]->getSource() );
		$this->assertSame( 'lastName', $validationResult->getViolations()[3]->getSource() );
	}

	public function testGivenTooLongCompanyValues_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validateCompanyName(
			str_repeat( 'a', 101 )
		);
		$this->assertFalse( $validationResult->isSuccessful() );
		$this->assertCount( 1, $validationResult->getViolations() );
		$this->assertSame( 'companyName', $validationResult->getViolations()[0]->getSource() );
	}

	public function testGivenEmptyPostalValues_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePostalAddress(
			'',
			'',
			'',
			''
		);
		$this->assertFalse( $validationResult->isSuccessful() );
		$violations = array_values( $validationResult->getViolations() );
		$this->assertCount( 4, $violations );
		$this->assertSame( 'street', $violations[0]->getSource() );
		$this->assertSame( 'postcode', $violations[1]->getSource() );
		$this->assertSame( 'city', $violations[2]->getSource() );
		$this->assertSame( 'country', $violations[3]->getSource() );
	}

	public function testGivenEmptyNameValues_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePersonName(
			'',
			'',
			'',
			''
		);
		$this->assertFalse( $validationResult->isSuccessful() );
		// Title is optional, no violation expected here
		$this->assertCount( 3, $validationResult->getViolations() );
		$this->assertSame( 'salutation', $validationResult->getViolations()[0]->getSource() );
		$this->assertSame( 'firstName', $validationResult->getViolations()[1]->getSource() );
		$this->assertSame( 'lastName', $validationResult->getViolations()[2]->getSource() );
	}

	public function testGivenEmptyCompanyValues_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validateCompanyName( '' );
		$this->assertFalse( $validationResult->isSuccessful() );
		$this->assertCount( 1, $validationResult->getViolations() );
		$this->assertSame( 'companyName', $validationResult->getViolations()[0]->getSource() );
	}

	public function testGivenBadPostcodeForCountry_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePostalAddress( 'Test 1234', '123', 'Test City', 'DE' );
		$this->assertSame( 'postcode', $validationResult->getViolations()[0]->getSource() );
	}

	public function testGivenBadFirstAndLastName_correctViolationsAreReturned(): void {
		$validator = new AddressValidator( self::COUNTRY_POSTCODE_PATTERNS, self::ADDRESS_PATTERNS );
		$validationResult = $validator->validatePersonName( 'Herr', '', '£$%^&*()', '£$%^&*()' );
		$this->assertSame( 'firstName', $validationResult->getViolations()[0]->getSource() );
		$this->assertSame( 'lastName', $validationResult->getViolations()[1]->getSource() );
	}
}
