<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface DomainNameValidator {

	public function isValid( string $domain ): bool;

}
