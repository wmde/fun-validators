<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

/**
 * @license GNU GPL v2+
 * @author Gabriel Birke < gabriel.birke@wikimedia.de >
 */
class RequiredFieldValidator {

	public function validate( $value ): ValidationResult {    // @codingStandardsIgnoreLine
		if ( $value === '' ) {
			return new ValidationResult( new ConstraintViolation( $value, 'field_required' ) );
		}

		return new ValidationResult();
	}

}