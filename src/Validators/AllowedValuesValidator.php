<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

/**
 * @license GPL-2.0-or-later
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class AllowedValuesValidator {

	private $allowedValues;

	/**
	 * @param array $allowedValues
	 *
	 * @throws \UnexpectedValueException
	 */
	public function __construct( array $allowedValues ) {
		if ( empty( $allowedValues ) ) {
			throw new \UnexpectedValueException( 'You must initialize with at least 1 allowed value' );
		}
		$this->allowedValues = $allowedValues;
	}

	public function validate( $value ): ValidationResult {    // @codingStandardsIgnoreLine
		if ( in_array( $value, $this->allowedValues, true ) ) {
			return new ValidationResult();
		}
		return new ValidationResult( new ConstraintViolation( $value, 'Not an allowed value' ) );
	}
}
