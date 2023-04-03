<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

interface StringList {

	/**
	 * @return string[]
	 */
	public function toArray(): array;

}
