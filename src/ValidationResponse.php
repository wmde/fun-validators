<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

class ValidationResponse {

	/**
	 * @param ConstraintViolation[] $validationErrors
	 * @param bool $needsModerationValue
	 */
	public function __construct(
		private array $validationErrors = [],
		private bool $needsModerationValue = false
	) {
	}

	public static function newSuccessResponse(): self {
		return new self();
	}

	/**
	 * @param ConstraintViolation[] $errors
	 */
	public static function newFailureResponse( array $errors ): self {
		return new self( $errors );
	}

	public static function newModerationNeededResponse(): self {
		return new self( [], true );
	}

	/**
	 * @return ConstraintViolation[]
	 */
	public function getValidationErrors(): array {
		return $this->validationErrors;
	}

	public function isSuccessful(): bool {
		return count( $this->validationErrors ) == 0;
	}

	public function needsModeration(): bool {
		return $this->needsModerationValue;
	}

}
