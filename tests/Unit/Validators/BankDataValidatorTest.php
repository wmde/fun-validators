<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit\Validators;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\Validators\BankDataValidator;

#[CoversClass( BankDataValidator::class )]
class BankDataValidatorTest extends TestCase {

	public const string VALID_BIC = 'INGDDEFFXXX';
	public const string VALID_BIC_SHORT = 'INGDDEFF';
	public const string VALID_IBAN = 'DE12500105170648489890';

	public const string INVALID_COUNTRYCODE_IBAN = '1E12500105170648489890';
	public const string UNSUPPORTED_COUNTRYCODE_IBAN = 'XE12500105170648489890';
	public const string UNSUPPORTED_IBAN = 'XE12500105170648489890';
	public const string INVALID_IRISH_IBAN = 'IE29AIBK931152123456781';
	public const string VALID_IRISH_BIC = 'BOFIIE2D';
	public const string INVALID_COUNTRYCODE_BIC = 'INGDDXFF';

	public function testValidatesIbanAndBic(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, self::VALID_BIC );

		$this->assertCount( 0, $result->getViolations() );
	}

	public function testIbanIsRequired(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( '', self::VALID_BIC );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'iban_required', $result->getViolations()[0]->getMessageIdentifier() );
	}

	public function testValidatesIbanWithWhitespace(): void {
		$ibanWithWhitespace = implode( ' ', str_split( self::VALID_IBAN, 4 ) );
		$validator = new BankDataValidator();

		$result = $validator->validate( $ibanWithWhitespace, self::VALID_BIC );

		$this->assertCount( 0, $result->getViolations() );
	}

	public function testIbanMustBeAlphanum(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( '(´•ω•`)', self::VALID_BIC );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'iban_alphanumeric', $result->getViolations()[0]->getMessageIdentifier() );
	}

	public function testIbanMustHaveValidCountryCode(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::INVALID_COUNTRYCODE_IBAN, self::VALID_BIC );

		$this->assertCount( 2, $result->getViolations() );
		$this->assertEquals( 'iban_invalid_countrycode', $result->getViolations()[0]->getMessageIdentifier() );
		$this->assertEquals( 'bic_iban_different_country_code', $result->getViolations()[1]->getMessageIdentifier() );
	}

	public function testIbanMustHaveSupportedCountryCode(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::UNSUPPORTED_COUNTRYCODE_IBAN, self::VALID_BIC );

		$this->assertCount( 2, $result->getViolations() );
		$this->assertEquals( 'iban_countrycode_not_supported', $result->getViolations()[0]->getMessageIdentifier() );
		$this->assertEquals( 'bic_iban_different_country_code', $result->getViolations()[1]->getMessageIdentifier() );
	}

	public function testIbanMustBeValid(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::INVALID_IRISH_IBAN, self::VALID_IRISH_BIC );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'iban_invalid_format', $result->getViolations()[0]->getMessageIdentifier() );
	}

	public function testAcceptsShortBic(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, self::VALID_BIC_SHORT );

		$this->assertCount( 0, $result->getViolations() );
	}

	public function testBicIsRequired(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, '' );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'bic_required', $result->getViolations()[0]->getMessageIdentifier() );
	}

	public function testValidatesBicWithWhitespace(): void {
		$bicWithWhitespace = implode( ' ', str_split( self::VALID_BIC, 2 ) );
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, $bicWithWhitespace );

		$this->assertCount( 0, $result->getViolations() );
	}

	public function testBicMustBeCorrectLength(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, self::VALID_BIC . '22' );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'bic_invalid_length', $result->getViolations()[0]->getMessageIdentifier() );
	}

	public function testBicMustBeAlphaNumeric(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, '==(**/**)==' );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'bic_alphanumeric', $result->getViolations()[0]->getMessageIdentifier() );
	}

	public function testInvalidBicCountryCode(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, self::INVALID_COUNTRYCODE_BIC );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'bic_invalid_country_code', $result->getViolations()[0]->getMessageIdentifier() );
	}

	public function testMismatchedIbanAndBicCountryCodes(): void {
		$validator = new BankDataValidator();

		$result = $validator->validate( self::VALID_IBAN, self::VALID_IRISH_BIC );

		$this->assertCount( 1, $result->getViolations() );
		$this->assertEquals( 'bic_iban_different_country_code', $result->getViolations()[0]->getMessageIdentifier() );
	}
}
