<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Tests\System;

use PHPUnit\Framework\TestCase;
use WMDE\FunValidators\Validators\TextPolicyValidator;

/**
 * @covers \WMDE\FunValidators\Validators\TextPolicyValidator
 *
 * @license GPL-2.0-or-later
 * @author Christoph Fischer < christoph.fischer@wikimedia.de >
 */
class TextPolicyValidatorTest extends TestCase {

	/**
	 * @dataProvider urlTestProvider
	 *
	 * @param string $commentToTest
	 */
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
		} catch ( \Exception $exception ) {
			$this->markTestSkipped( 'No internet connection' );
		}
	}

	public function urlTestProvider(): array {
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
	 * @dataProvider harmlessTestProvider
	 *
	 * @param string $commentToTest
	 */
	public function testWhenGivenHarmlessComment_validatorReturnsTrue( string $commentToTest ): void {
		$this->skipIfNoInternet();

		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertTrue( $textPolicyValidator->hasHarmlessContent(
			$commentToTest,
			TextPolicyValidator::CHECK_URLS | TextPolicyValidator::CHECK_URLS_DNS | TextPolicyValidator::CHECK_BADWORDS
		) );
	}

	public function harmlessTestProvider(): array {
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
			TextPolicyValidator::CHECK_URLS | TextPolicyValidator::CHECK_URLS_DNS | TextPolicyValidator::CHECK_BADWORDS
		) );
	}

	/**
	 * @dataProvider insultingTestProvider
	 *
	 * @param string $commentToTest
	 */
	public function testWhenGivenInsultingComment_validatorReturnsFalse( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertFalse( $textPolicyValidator->hasHarmlessContent(
			$commentToTest,
			TextPolicyValidator::CHECK_BADWORDS
		) );
	}

	public function insultingTestProvider(): array {
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
	 * @dataProvider whiteWordsInsultingTestProvider
	 *
	 * @param string $commentToTest
	 */
	public function testWhenGivenInsultingCommentAndWhiteWords_validatorReturnsFalse( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertFalse(
			$textPolicyValidator->hasHarmlessContent(
				$commentToTest,
				TextPolicyValidator::CHECK_URLS
				| TextPolicyValidator::CHECK_URLS_DNS
				| TextPolicyValidator::CHECK_BADWORDS
				| TextPolicyValidator::IGNORE_WHITEWORDS
			)
		);
	}

	public function whiteWordsInsultingTestProvider(): array {
		return [
			[ 'Ich heisse Deppendorf ihr Deppen und das ist auch gut so!' ],
			[ 'Ihr Arschgeigen, ich wohne in Marsch und das ist auch gut so!' ],
			[ 'Bei Wikipedia gibts echt tolle Arschkrampen!' ],
		];
	}

	/**
	 * @dataProvider whiteWordsHarmlessTestProvider
	 *
	 * @param string $commentToTest
	 */
	public function testWhenGivenHarmlessCommentAndWhiteWords_validatorReturnsTrue( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertTrue(
			$textPolicyValidator->hasHarmlessContent(
				$commentToTest,
				TextPolicyValidator::CHECK_URLS
				| TextPolicyValidator::CHECK_URLS_DNS
				| TextPolicyValidator::CHECK_BADWORDS
				| TextPolicyValidator::IGNORE_WHITEWORDS
			)
		);
	}

	public function whiteWordsHarmlessTestProvider(): array {
		return [
			[ 'Wikipedia ist so super, meine Eltern sagen es ist eine toll Seite. Berlin ist auch Super.' ],
			[ 'Ich heisse Deppendorf ihr und das ist auch gut so!' ],
			[ 'Bei Wikipedia gibts echt tolle Dinge!' ],
			[ 'Ick spend richtig Kohle, denn ick hab ne GmbH & Co.KG' ],
		];
	}

	/**
	 * @dataProvider insultingTestProviderWithRegexChars
	 *
	 * @param string $commentToTest
	 */
	public function testGivenBadWordMatchContainingRegexChars_validatorReturnsFalse( string $commentToTest ): void {
		$textPolicyValidator = $this->getPreFilledTextPolicyValidator();

		$this->assertFalse(
			$textPolicyValidator->hasHarmlessContent(
				$commentToTest,
				TextPolicyValidator::CHECK_URLS
				| TextPolicyValidator::CHECK_URLS_DNS
				| TextPolicyValidator::CHECK_BADWORDS
				| TextPolicyValidator::IGNORE_WHITEWORDS
			)
		);
	}

	public function insultingTestProviderWithRegexChars(): array {
		return [
			[ 'Ich heisse Deppendorf (ihr Deppen und das ist auch gut so!' ],
			[ 'Ihr [Arschgeigen], ich wohne in //Marsch// und das ist auch gut so!' ],
			[ 'Bei #Wikipedia gibts echt tolle Arschkrampen!' ],
		];
	}

	private function getPreFilledTextPolicyValidator(): TextPolicyValidator {
		$textPolicyValidator = new TextPolicyValidator();
		$textPolicyValidator->addBadWordsFromArray(
			[
				'deppen',
				'hitler',
				'hamsterfresse',
				'arsch',
				'sieg heil'
			] );
		$textPolicyValidator->addWhiteWordsFromArray(
			[
				'Deppendorf',
				'Marsch',
				'Co.KG',
			] );
		return $textPolicyValidator;
	}

}
