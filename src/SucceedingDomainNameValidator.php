<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @licence GNU GPL v2+
 */
class SucceedingDomainNameValidator implements DomainNameValidator {

	public function isValid( string $domain ): bool {
		return true;
	}

}
