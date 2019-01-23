<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ValidationResult;
use WMDE\FunValidators\SucceedingDomainNameValidator;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SucceedingEmailValidator extends EmailValidator {

	public function __construct() {
		parent::__construct( new SucceedingDomainNameValidator() );
	}

	public function validate( string $emailAddress ): ValidationResult {
		return new ValidationResult();
	}

}
