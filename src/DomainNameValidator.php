<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface DomainNameValidator {

	public function isValid( string $domain ): bool;

}
