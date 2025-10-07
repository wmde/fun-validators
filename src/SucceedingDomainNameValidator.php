<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

class SucceedingDomainNameValidator implements DomainNameValidator {

	public function isValid( string $domain ): bool {
		return true;
	}

}
