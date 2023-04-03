<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

class ConstraintViolation {

	/**
	 * @param mixed $value The value that caused this violation
	 * @param string $messageIdentifier identifier of the error message as defined in translation files
	 * @param string $source Class name or Class.Field name
	 */
	public function __construct(
		private $value,
		private string $messageIdentifier,
		private string $source = '' ) {
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	public function getMessageIdentifier(): string {
		return $this->messageIdentifier;
	}

	public function getSource(): string {
		return $this->source;
	}

	public function setSource( string $source ): void {
		$this->source = $source;
	}

}
