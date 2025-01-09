<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @deprecated this trait is used in other repositories and cannot be tested here properly,
 * thus it should be turned into a helper class instead or get the behaviour extracted in another way
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
