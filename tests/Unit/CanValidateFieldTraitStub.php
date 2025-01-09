<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit;

use WMDE\FunValidators\CanValidateField;
use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

class CanValidateFieldTraitStub {

	use CanValidateField;

	public function publicWrapperMethodForGetFieldViolationMethod( ValidationResult $validationResult ): ?ConstraintViolation {
		return $this->getFieldViolation(
			$validationResult,
			"a field name"
		);
	}

}
