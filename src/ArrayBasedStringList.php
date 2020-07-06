<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators;

/**
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ArrayBasedStringList implements StringList {

	private $arrayOfString;

	public function __construct( array $arrayOfString ) {
		$this->arrayOfString = $arrayOfString;
	}

	/**
	 * @return string[]
	 */
	public function toArray(): array {
		return $this->arrayOfString;
	}

}
