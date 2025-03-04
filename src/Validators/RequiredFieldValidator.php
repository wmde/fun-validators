<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

class RequiredFieldValidator {

	public function validate( string $value ): ValidationResult {    // @codingStandardsIgnoreLine
		if ( $value === '' ) {
			return new ValidationResult( new ConstraintViolation( $value, 'field_required' ) );
		}

		return new ValidationResult();
	}

}
