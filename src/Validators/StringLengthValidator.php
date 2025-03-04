<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

class StringLengthValidator {

	public function validate( string $value, int $maxLength, int $minLength = 0 ): ValidationResult {    // @codingStandardsIgnoreLine
		if ( strlen( $value ) < $minLength || strlen( $value ) > $maxLength ) {
			return new ValidationResult( new ConstraintViolation( $value, 'incorrect_length' ) );
		}

		return new ValidationResult();
	}

}
