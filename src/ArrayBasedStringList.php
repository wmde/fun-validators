<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

class ArrayBasedStringList implements StringList {

	/**
	 * @param string[] $arrayOfString
	 */
	public function __construct( private array $arrayOfString ) {
	}

	/**
	 * @return string[]
	 */
	public function toArray(): array {
		return $this->arrayOfString;
	}

}
