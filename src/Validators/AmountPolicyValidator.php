<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ConstraintViolation;
use WMDE\FunValidators\ValidationResult;

/**
 * @license GPL-2.0-or-later
 * @author Kai Nissen < kai.nissen@wikimedia.de >
 */
class AmountPolicyValidator {

	private $maxAmountOneTime;
	private $maxAmountRecurringAnnually;

	private const VIOLATION_TOO_HIGH = 'too_high';

	public function __construct( int $maxAmountOneTime, int $maxAmountRecurringAnnually ) {
		$this->maxAmountOneTime = $maxAmountOneTime;
		$this->maxAmountRecurringAnnually = $maxAmountRecurringAnnually;
	}

	public function validate( float $amount, int $interval ): ValidationResult {
		if ( $this->isOneTimeAmountTooHigh( $amount, $interval ) ||
			$this->isAnuallyRecurringAmountTooHigh( $amount, $interval ) ) {
			return new ValidationResult( new ConstraintViolation( $amount, self::VIOLATION_TOO_HIGH ) );
		}

		return new ValidationResult();
	}

	private function isOneTimeAmountTooHigh( float $amount, int $interval ): bool {
		if ( $interval === 0 ) {
			return $amount >= $this->maxAmountOneTime;
		}
		return false;
	}

	private function isAnuallyRecurringAmountTooHigh( float $amount, int $interval ): bool {
		if ( $interval > 0 ) {
			return ( 12 / $interval ) * $amount >= $this->maxAmountRecurringAnnually;
		}
		return false;
	}

}
