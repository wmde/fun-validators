<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

interface DomainNameValidator {

	public function isValid( string $domain ): bool;

}
