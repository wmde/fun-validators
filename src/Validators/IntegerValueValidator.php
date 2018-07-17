<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

/**
 * @license GNU GPL v2+
 */
class IntegerValueValidator {

	public function validate( string $input ): ValidationResult {
		if ( !ctype_digit( $input ) ) {
			return new ValidationResult( new ConstraintViolation( $input, 'field_numeric' ) );
		}

		return new ValidationResult();
	}

}
