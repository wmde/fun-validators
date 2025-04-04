<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\Unit\Validators;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\DomainNameValidator;
use WMDE\FunValidators\SucceedingDomainNameValidator;
use WMDE\FunValidators\Validators\EmailValidator;

#[CoversClass( EmailValidator::class )]
class EmailValidatorTest extends TestCase {

	private function newStubDomainValidator(): DomainNameValidator {
		return new class() implements DomainNameValidator {
			public function isValid( string $domain ): bool {
				return in_array(
					$domain,
					[
						'wikimedia.de',
						'nick.berlin',
						'xn--triebwerk-grn-7ob.de',
						'xn--4gbrim.xn----ymcbaaajlc6dj7bxne2c.xn--wgbh1c'
					]
				);
			}
		};
	}

	/**
	 * @param string $validEmail
	 */
	#[DataProvider( 'fullyValidEmailProvider' )]
	public function testGivenValidMail_validationWithDomainNameCheckSucceeds( string $validEmail ): void {
		$mailValidator = new EmailValidator( $this->newStubDomainValidator() );

		$this->assertTrue( $mailValidator->validate( $validEmail )->isSuccessful() );
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function fullyValidEmailProvider(): array {
		return [
			[ 'christoph.fischer@wikimedia.de' ],
			[ 'test@nick.berlin' ],
			[ 'A-Za-z0-9.!#$%&\'*+-/=?^_`{|}~info@nick.berlin' ],
			[ 'info@triebwerk-grün.de' ],
			[ 'info@triebwerk-grün.de' ],
			[ 'info@موقع.وزارة-الاتصالات.مصر' ],
		];
	}

	/**
	 * @param string $invalidEmail
	 */
	#[DataProvider( 'emailWithInvalidDomainProvider' )]
	public function testGivenMailWithInvalidDomain_validationWithDomainNameCheckFails( string $invalidEmail ): void {
		$mailValidator = new EmailValidator( $this->newStubDomainValidator() );

		$this->assertFalse( $mailValidator->validate( $invalidEmail )->isSuccessful() );
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function emailWithInvalidDomainProvider(): array {
		return [
			[ 'chrifi.asfsfas.de  ' ],
			[ ' ' ],
			[ 'fibor@fgagaadadfafasfasfasfasffasfsfe.com' ],
			[ 'hllo909a()_9a=f9@dsafadsff' ],
			[ 'christoph.fischer@wikimedia.de ' ],
			[ 'christoph.füscher@wikimedia.de ' ],
			[ 'ich@ort...' ]
		];
	}

	/**
	 * @param string $invalidEmail
	 */
	#[DataProvider( 'emailWithInvalidFormatProvider' )]
	public function testGivenMailWithInvalidFormat_validationWithoutDomainCheckFails( string $invalidEmail ): void {
		$mailValidator = new EmailValidator( new SucceedingDomainNameValidator() );

		$this->assertFalse( $mailValidator->validate( $invalidEmail )->isSuccessful() );
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function emailWithInvalidFormatProvider(): array {
		return [
			[ 'chrifi.asfsfas.de  ' ],
			[ ' ' ],
			[ 'hllo909a()_9a=f9@dsafadsff' ],
			[ 'christoph.fischer@wikimedia.de ' ],
			[ 'christoph.füscher@wikimedia.de ' ],
		];
	}

}
