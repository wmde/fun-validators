<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @license GPL-2.0-or-later
 */
class SucceedingDomainNameValidator implements DomainNameValidator {

	public function isValid( string $domain ): bool {
		return true;
	}

}
