<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

class ValidationResult {

	/**
	 * @var ConstraintViolation[]
	 */
	private array $violations;

	public function __construct( ConstraintViolation ...$violations ) {
		$this->violations = $violations;
	}

	public function isSuccessful(): bool {
		return empty( $this->violations );
	}

	public function hasViolations(): bool {
		return !empty( $this->violations );
	}

	/**
	 * @return ConstraintViolation[]
	 */
	public function getViolations(): array {
		return $this->violations;
	}

}
