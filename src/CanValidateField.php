<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @deprecated Use {@see WMDE\FunValidators\ValidationResult::setSource()} instead
 */
trait CanValidateField {

	private function getFieldViolation( ValidationResult $validationResult, string $fieldName ): ?ConstraintViolation {
		if ( $validationResult->isSuccessful() ) {
			return null;
		}

		$violation = $validationResult->getViolations()[0];
		$violation->setSource( $fieldName );

		return $violation;
	}

}
