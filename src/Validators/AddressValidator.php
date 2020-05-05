<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

/**
 * Note that this address validator is tailored to the fundraising use case of Wikimedia Deutschland
 * and does not validate all addresses and names across the globe properly.
 */
class AddressValidator {

	private const VIOLATION_MISSING = 'missing';
	private const VIOLATION_NOT_POSTCODE = 'not-postcode';
	private const VIOLATION_WRONG_LENGTH = 'wrong-length';

	public const SOURCE_COMPANY = 'companyName';
	public const SOURCE_FIRST_NAME = 'firstName';
	public const SOURCE_LAST_NAME = 'lastName';
	public const SOURCE_SALUTATION = 'salutation';
	public const SOURCE_TITLE = 'title';
	public const SOURCE_STREET_ADDRESS = 'street';
	public const SOURCE_POSTAL_CODE = 'postcode';
	public const SOURCE_CITY = 'city';
	public const SOURCE_COUNTRY = 'country';

	private $maximumFieldLengths = [
		self::SOURCE_COMPANY => 100,
		self::SOURCE_FIRST_NAME => 50,
		self::SOURCE_LAST_NAME => 50,
		self::SOURCE_SALUTATION => 16,
		self::SOURCE_TITLE => 16,
		self::SOURCE_STREET_ADDRESS => 100,
		self::SOURCE_CITY => 100,
		self::SOURCE_COUNTRY => 8,
		self::SOURCE_POSTAL_CODE => 16,
	];

	private $countriesPostcodePatterns;

	public function __construct( array $countriesPostcodePatterns ) {
		$this->countriesPostcodePatterns = $countriesPostcodePatterns;
	}

	public function validatePostalAddress( string $streetAddress, string $postalCode, string $city, string $countryCode ): ValidationResult {
		$violations = [];

		if ( $streetAddress === '' ) {
			$violations[] = new ConstraintViolation(
				$streetAddress,
				self::VIOLATION_MISSING,
				self::SOURCE_STREET_ADDRESS
			);
		} else {
			$violations[] = $this->validateFieldLength( $streetAddress, self::SOURCE_STREET_ADDRESS );
		}

		if ( isset( $this->countriesPostcodePatterns[$countryCode] ) ) {
			$violations[] = $this->validatePostalCode( $this->countriesPostcodePatterns[$countryCode], $postalCode );
		} else {
			$postalCodeLengthViolation = $this->validateFieldLength( $postalCode, self::SOURCE_POSTAL_CODE );
			if ( $postalCodeLengthViolation === null ) {
				$violations[] = $this->validatePostalCode( '/^.+$/', $postalCode );
			} else {
				$violations[] = $postalCodeLengthViolation;
			}
		}

		if ( $city === '' ) {
			$violations[] = new ConstraintViolation(
				$city,
				self::VIOLATION_MISSING,
				self::SOURCE_CITY
			);
		} else {
			$violations[] = $this->validateFieldLength( $city, self::SOURCE_CITY );
		}

		if ( $countryCode === '' ) {
			$violations[] = new ConstraintViolation(
				$countryCode,
				self::VIOLATION_MISSING,
				self::SOURCE_COUNTRY
			);
		} else {
			$violations[] = $this->validateFieldLength( $countryCode, self::SOURCE_COUNTRY );
		}

		return new ValidationResult( ...array_filter( $violations ) );
	}

	private function validatePostalCode( string $pattern, string $postalCode ): ?ConstraintViolation {
		if ( !preg_match( $pattern, $postalCode ) ) {
			return new ConstraintViolation(
				$postalCode,
				self::VIOLATION_NOT_POSTCODE,
				self::SOURCE_POSTAL_CODE
			);
		}
		return null;
	}

	public function validatePersonName( string $salutation, string $title, string $firstname, string $lastname ): ValidationResult {
		$violations = [];

		if ( $salutation === '' ) {
			$violations[] = new ConstraintViolation(
				$salutation,
				self::VIOLATION_MISSING,
				self::SOURCE_SALUTATION
			);
		} else {
			$violations[] = $this->validateFieldLength( $salutation, self::SOURCE_SALUTATION );
		}

		$violations[] = $this->validateFieldLength( $title, self::SOURCE_TITLE );

		if ( $firstname === '' ) {
			$violations[] = new ConstraintViolation(
				$firstname,
				self::VIOLATION_MISSING,
				self::SOURCE_FIRST_NAME
			);
		} else {
			$violations[] = $this->validateFieldLength( $firstname, self::SOURCE_FIRST_NAME );
		}

		if ( $lastname === '' ) {
			$violations[] = new ConstraintViolation(
				$lastname,
				self::VIOLATION_MISSING,
				self::SOURCE_LAST_NAME
			);
		} else {
			$violations[] = $this->validateFieldLength( $lastname, self::SOURCE_LAST_NAME );
		}
		return new ValidationResult( ...array_filter( $violations ) );
	}

	public function validateCompanyName( string $companyName ): ValidationResult {
		$violations = [];
		if ( $companyName === '' ) {
			$violations[] = new ConstraintViolation(
				$companyName,
				self::VIOLATION_MISSING,
				self::SOURCE_COMPANY
			);
		} else {
			$violations[] = $this->validateFieldLength( $companyName, self::SOURCE_COMPANY );
		}
		return new ValidationResult( ...array_filter( $violations ) );
	}

	private function validateFieldLength( string $value, string $fieldName ): ?ConstraintViolation {
		if ( strlen( $value ) > $this->maximumFieldLengths[$fieldName] ) {
			return new ConstraintViolation(
				$value,
				self::VIOLATION_WRONG_LENGTH,
				$fieldName
			);
		}
		return null;
	}
}
