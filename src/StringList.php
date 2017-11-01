<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface StringList {

	/**
	 * @return string[]
	 */
	public function toArray(): array;

}
