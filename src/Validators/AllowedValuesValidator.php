<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

class AllowedValuesValidator {

	/**
	 * @param array $allowedValues
	 *
	 * @throws \UnexpectedValueException
	 */
	public function __construct( private array $allowedValues ) {
		if ( empty( $allowedValues ) ) {
			throw new \UnexpectedValueException( 'You must initialize with at least 1 allowed value' );
		}
	}

	/**
	 * @param mixed $value
	 */
	public function validate( $value ): ValidationResult {
		if ( in_array( $value, $this->allowedValues, true ) ) {
			return new ValidationResult();
		}
		return new ValidationResult( new ConstraintViolation( $value, 'Not an allowed value' ) );
	}
}
