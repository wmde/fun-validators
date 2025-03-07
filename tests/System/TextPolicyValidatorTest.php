<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\System;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\Validators\TextPolicyValidator;

#[CoversClass( TextPolicyValidator::class )]
class TextPolicyValidatorTest extends TestCase {

	/**
	 * @param string $commentToTest
	 */
	#[DataProvider( 'urlTestProvider' )]
	public function testWhenGivenCommentHasURL_validatorReturnsFalse( string $commentToTest ): void {
		$this->skipIfNoInternet();

		$textPolicyValidator = new TextPolicyValidator();

		$this->assertFalse( $textPolicyValidator->hasHarmlessContent(
			$commentToTest,
			TextPolicyValidator::CHECK_URLS | TextPolicyValidator::CHECK_URLS_DNS
		) );
	}

	private function skipIfNoInternet(): void {
		try {
			if ( !(bool)fsockopen( 'www.google.com', 80, $num, $error, 1 ) ) {
				$this->markTestSkipped( 'No internet connection' );
			}
		} catch ( Exception $exception ) {
			$this->markTestSkipped( 'No internet connection' );
		}
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function urlTestProvider(): array {
		return [
			[ 'www.example.com' ],
			[ 'http://www.example.com' ],
			[ 'https://www.example.com' ],
			[ 'example.com' ],
			[ 'example.com/test' ],
			[ 'example.com/teKAst/index.php' ],
			[ 'Ich mag Wikipedia. Aber meine Seite ist auch toll:example.com/teKAst/index.php' ],
			[ 'inwx.berlin' ],
			[ 'wwwwwww.website.com' ],
			[ 'TriebWerk-Grün.de' ],
		];
	}

	/**
	 * @param string $commentToTest
	 */
	#[DataProvider( 'harmlessTestProvider' )]
	public function testWhenGivenHarmlessComment_validatorReturnsTrue( string $commentToTest ): void {
		$this->skipIfNoInternet();

		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertTrue( $textPolicyValidator->hasHarmlessContent(
			$commentToTest,
			TextPolicyValidator::CHECK_URLS | TextPolicyValidator::CHECK_URLS_DNS | TextPolicyValidator::CHECK_DENIED_WORDS
		) );
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function harmlessTestProvider(): array {
		return [
			[ 'Wikipedia ist so super, meine Eltern sagen es ist eine toll Seite. Berlin ist auch Super.' ],
			[ 'Ich mag Wikipedia. Aber meine Seite ist auch toll. Googelt mal nach Bunsenbrenner!!!1' ],
			[ 'Bei Wikipedia kann man eine Menge zum Thema Hamster finden. Hamster fressen voll viel Zeug alter!' ],
			// this also tests the domain detection
			[ 'Manche Seiten haben keinen Inhalt, das finde ich sch...e' ],
		];
	}

	public function testHarmlessContentWithDns(): void {
		$this->skipIfNoInternet();

		if ( checkdnsrr( 'some-non-existing-domain-drfeszrfdaesr.sdferdyerdhgty', 'A' ) ) {
			// https://www.youtube.com/watch?v=HGBOeLdm-1s
			$this->markTestSkipped( 'Your DNS/ISP provider gives results for impossible host names.' );
		}

		$textPolicyValidator = new TextPolicyValidator();

		$this->assertTrue( $textPolicyValidator->hasHarmlessContent(
			'Ich mag Wikipedia.Wieso ? Weil ich es so toll finde!',
			TextPolicyValidator::CHECK_URLS | TextPolicyValidator::CHECK_URLS_DNS | TextPolicyValidator::CHECK_DENIED_WORDS
		) );
	}

	/**
	 * @param string $commentToTest
	 */
	#[DataProvider( 'insultingTestProvider' )]
	public function testWhenGivenInsultingComment_validatorReturnsFalse( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertFalse( $textPolicyValidator->hasHarmlessContent(
			$commentToTest,
			TextPolicyValidator::CHECK_DENIED_WORDS
		) );
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function insultingTestProvider(): array {
		return [
			[ 'Alles Deppen!' ],
			[ 'Heil Hitler!' ],
			[ 'Duhamsterfresse!!!' ],
			[ 'Alles nur HAMSTERFRESSEN!!!!!!!!1111111111' ],
			[ 'SiegHeil' ],
			[ 'Sieg Heil' ],
			[ "Sieg    \n\tHeil!" ]
		];
	}

	/**
	 * @param string $commentToTest
	 */
	#[DataProvider( 'allowedWordsInsultingTestProvider' )]
	public function testWhenGivenInsultingCommentAndAllowedWords_validatorReturnsFalse( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertFalse(
			$textPolicyValidator->hasHarmlessContent(
				$commentToTest,
				TextPolicyValidator::CHECK_URLS
				| TextPolicyValidator::CHECK_URLS_DNS
				| TextPolicyValidator::CHECK_DENIED_WORDS
				| TextPolicyValidator::IGNORE_ALLOWED_WORDS
			)
		);
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function allowedWordsInsultingTestProvider(): array {
		return [
			[ 'Ich heisse Deppendorf ihr Deppen und das ist auch gut so!' ],
			[ 'Ihr Arschgeigen, ich wohne in Marsch und das ist auch gut so!' ],
			[ 'Bei Wikipedia gibts echt tolle Arschkrampen!' ],
		];
	}

	/**
	 * @param string $commentToTest
	 */
	#[DataProvider( 'allowedWordsHarmlessTestProvider' )]
	public function testWhenGivenHarmlessCommentAndAllowedWords_validatorReturnsTrue( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertTrue(
			$textPolicyValidator->hasHarmlessContent(
				$commentToTest,
				TextPolicyValidator::CHECK_URLS
				| TextPolicyValidator::CHECK_URLS_DNS
				| TextPolicyValidator::CHECK_DENIED_WORDS
				| TextPolicyValidator::IGNORE_ALLOWED_WORDS
			)
		);
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function allowedWordsHarmlessTestProvider(): array {
		return [
			[ 'Wikipedia ist so super, meine Eltern sagen es ist eine toll Seite. Berlin ist auch Super.' ],
			[ 'Ich heisse Deppendorf ihr und das ist auch gut so!' ],
			[ 'Bei Wikipedia gibts echt tolle Dinge!' ],
			[ 'Ick spend richtig Kohle, denn ick hab ne GmbH & Co.KG' ],
		];
	}

	/**
	 * @param string $commentToTest
	 */
	#[DataProvider( 'insultingTestProviderWithRegexChars' )]
	public function testGivenBadWordMatchContainingRegexChars_validatorReturnsFalse( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertFalse(
			$textPolicyValidator->hasHarmlessContent(
				$commentToTest,
				TextPolicyValidator::CHECK_URLS
				| TextPolicyValidator::CHECK_URLS_DNS
				| TextPolicyValidator::CHECK_DENIED_WORDS
				| TextPolicyValidator::IGNORE_ALLOWED_WORDS
			)
		);
	}

	/**
	 * @return array<int, array<int, string>>
	 */
	public static function insultingTestProviderWithRegexChars(): array {
		return [
			[ 'Ich heisse Deppendorf (ihr Deppen und das ist auch gut so!' ],
			[ 'Ihr [Arschgeigen], ich wohne in //Marsch// und das ist auch gut so!' ],
			[ 'Bei #Wikipedia gibts echt tolle Arschkrampen!' ],
		];
	}

	private function getPreFilledTextPolicyValidator(): TextPolicyValidator {
		$textPolicyValidator = new TextPolicyValidator();
		$textPolicyValidator->addDeniedWordsFromArray(
			[
				'deppen',
				'hitler',
				'hamsterfresse',
				'arsch',
				'sieg heil'
			] );
		$textPolicyValidator->addAllowedWordsFromArray(
			[
				'Deppendorf',
				'Marsch',
				'Co.KG',
			] );
		return $textPolicyValidator;
	}

}
