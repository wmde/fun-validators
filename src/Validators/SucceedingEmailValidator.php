<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\SucceedingDomainNameValidator;
use WMDE\FunValidators\ValidationResult;

/**
 * @license GPL-2.0-or-later
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
