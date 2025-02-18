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

	/**
	 * Set source of an error in all violations.
	 *
	 * This method is meant for validation results that come from one source,
	 * e.g. a validator like {@see WMDE\FunValidators\Validators\RequiredFieldValidator}
	 * or {@see WMDE\FunValidators\Validators\StringLengthValidator}
	 *
	 * DO NOT call it on validation results that come from validators
	 * that validate several sources. In those cases, the responsibility to
	 * set the source is with the validator and not its calling code.
	 */
	public function setSource( string $sourceName ): self {
		foreach ( $this->violations as $violation ) {
			$violation->setSource( $sourceName );
		}
		return $this;
	}

	public function getFirstViolation(): ?ConstraintViolation {
		return $this->violations[0] ?? null;
	}
}
