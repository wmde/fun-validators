<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\DomainNameValidator;
use WMDE\FunValidators\ValidationResult;
use const IDNA_NONTRANSITIONAL_TO_ASCII;
use const INTL_IDNA_VARIANT_UTS46;

class EmailValidator {

	public function __construct( private DomainNameValidator $domainValidator ) {
	}

	public function validate( string $emailAddress ): ValidationResult {
		$addressParts = explode( '@', $emailAddress );

		if ( !is_array( $addressParts ) || count( $addressParts ) !== 2 ) {
			return new ValidationResult( new ConstraintViolation( $emailAddress, 'email_address_wrong_format' ) );
		}

		$userName = $addressParts[0];
		$domain = $addressParts[1];

		if ( trim( $domain ) === '' ) {
			return new ValidationResult( new ConstraintViolation( $emailAddress, 'email_address_wrong_format' ) );
		}

		$normalizedDomain = (string)idn_to_ascii( $domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46 );

		if ( !filter_var( $userName . '@' . $normalizedDomain, FILTER_VALIDATE_EMAIL ) ) {
			return new ValidationResult( new ConstraintViolation( $emailAddress, 'email_address_invalid' ) );
		}

		if ( !$this->domainValidator->isValid( $normalizedDomain ) ) {
			return new ValidationResult(
				new ConstraintViolation( $emailAddress, 'email_address_domain_record_not_found' )
			);
		}

		return new ValidationResult();
	}

}
