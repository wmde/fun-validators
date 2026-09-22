<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\Data\Countries;
use WMDE\FunValidators\Data\IbanFormats;
use WMDE\FunValidators\ValidationResult;

/**
 * This code was pulled from the Symfony valididator repo and modified for use in our systems.
 * It validates the length, format, and country codes of IBANs and BICs
 * See: https://github.com/symfony/validator
 */
class BankDataValidator {

	public function validate( string $iban, string $bic ): ValidationResult {
		$violations = [];

		$iban = str_replace( ' ', '', strtoupper( $iban ) );
		$bic = str_replace( ' ', '', strtoupper( $bic ) );

		$ibanViolation = $this->validateIban( $iban );
		$bicViolation = $this->validateBic( $iban, $bic );

		if ( $ibanViolation !== null ) {
			$violations[] = $ibanViolation;
		}

		if ( $bicViolation !== null ) {
			$violations[] = $bicViolation;
		}

		return new ValidationResult( ...$violations );
	}

	private function validateIban( string $iban ): ?ConstraintViolation {
		if ( $iban === '' ) {
			return new ConstraintViolation( $iban, 'iban_required' );
		}

		if ( !ctype_alnum( $iban ) ) {
			return new ConstraintViolation( $iban, 'iban_alphanumeric' );
		}

		$countryCode = substr( $iban, 0, 2 );

		if ( !ctype_alpha( $countryCode ) ) {
			return new ConstraintViolation( $iban, 'iban_invalid_countrycode' );
		}

		if ( !\array_key_exists( $countryCode, IbanFormats::REGEXES ) ) {
			return new ConstraintViolation( $iban, 'iban_countrycode_not_supported' );
		}

		if ( !preg_match( '/^' . IbanFormats::REGEXES[$countryCode] . '$/', $iban ) ) {
			return new ConstraintViolation( $iban, 'iban_invalid_format' );
		}

		return null;
	}

	private function validateBic( string $iban, string $bic ): ?ConstraintViolation {
		if ( $bic === '' ) {
			return new ConstraintViolation( $bic, 'bic_required' );
		}

		// the bic must be either 8 or 11 characters long
		if ( !in_array( strlen( $bic ), [ 8, 11 ], true ) ) {
			return new ConstraintViolation( $bic, 'bic_invalid_length' );
		}

		if ( !ctype_alnum( $bic ) ) {
			return new ConstraintViolation( $bic, 'bic_alphanumeric' );
		}

		$bicCountryCode = substr( $bic, 4, 2 );

		if ( !isset( Countries::BIC_COUNTRY_TO_IBAN_COUNTRY_MAP[$bicCountryCode] ) && !in_array( $bicCountryCode, Countries::COUNTRY_CODES ) ) {
			return new ConstraintViolation( $bic, 'bic_invalid_country_code' );
		}

		$ibanCountryCode = substr( $iban, 0, 2 );

		if ( ctype_alnum( $ibanCountryCode ) && !$this->bicAndIbanCountriesMatch( $bicCountryCode, $ibanCountryCode ) ) {
			return new ConstraintViolation( $bic, 'bic_iban_different_country_code' );
		}

		return null;
	}

	private function bicAndIbanCountriesMatch( string $bicCountryCode, string $ibanCountryCode ): bool {
		return $ibanCountryCode === $bicCountryCode || $ibanCountryCode === ( Countries::BIC_COUNTRY_TO_IBAN_COUNTRY_MAP[$bicCountryCode] ?? null );
	}
}
