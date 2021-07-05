<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Validators;

use WMDE\FunValidators\ArrayBasedStringList;
use WMDE\FunValidators\StringList;

/**
 * @license GPL-2.0-or-later
 * @author Christoph Fischer < christoph.fischer@wikimedia.de >
 */
class TextPolicyValidator {

	private $deniedWords;
	private $allowedWords;

	public const CHECK_URLS = 1;

	public const CHECK_DENIED_WORDS = 4;
	/**
	 * @deprecated Remove this once removed from FundraisingFrontend. Related to: https://phabricator.wikimedia.org/T254646
	 */
	public const CHECK_BADWORDS = self::CHECK_DENIED_WORDS;

	public const IGNORE_ALLOWED_WORDS = 8;

	/**
	 * @deprecated Remove this once removed from FundraisingFrontend Related to: https://phabricator.wikimedia.org/T254646
	 */
	public const IGNORE_WHITEWORDS = self::IGNORE_ALLOWED_WORDS;

	// FIXME: this should be factored out as it (checkdnsrr) depends on internets
	// Could use an URL validation strategy
	public const CHECK_URLS_DNS = 2;

	public function __construct( StringList $deniedWords = null, StringList $allowedWords = null ) {
		$this->deniedWords = $deniedWords ?? new ArrayBasedStringList( [] );
		$this->allowedWords = $allowedWords ?? new ArrayBasedStringList( [] );
	}

	/**
	 * @return string[]
	 */
	private function getDeniedWords(): array {
		return $this->deniedWords->toArray();
	}

	/**
	 * @return string[]
	 */
	private function getAllowedWords(): array {
		return $this->allowedWords->toArray();
	}

	public function textIsHarmless( string $text ): bool {
		return $this->hasHarmlessContent(
			$text,
			self::CHECK_DENIED_WORDS
			| self::IGNORE_ALLOWED_WORDS
			| self::CHECK_URLS
		);
	}

	public function hasHarmlessContent( string $text, int $flags ): bool {
		$ignoreAllowedWords = (bool)( $flags & self::IGNORE_ALLOWED_WORDS );

		if ( $flags & self::CHECK_URLS ) {
			$testWithDNS = (bool)( $flags & self::CHECK_URLS_DNS );

			if ( $this->hasUrls( $text, $testWithDNS, $ignoreAllowedWords ) ) {
				return false;
			}
		}

		if ( $flags & self::CHECK_DENIED_WORDS ) {
			if ( count( $this->getDeniedWords() ) > 0 && $this->hasDeniedWords( $text, $ignoreAllowedWords ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param string[] $newDeniedWordsArray
	 */
	public function addDeniedWordsFromArray( array $newDeniedWordsArray ): void {
		$this->deniedWords = new ArrayBasedStringList( array_merge( $this->getDeniedWords(), $newDeniedWordsArray ) );
	}

	/**
	 * @param string[] $newAllowedWordsArray
	 */
	public function addAllowedWordsFromArray( array $newAllowedWordsArray ): void {
		$this->allowedWords = new ArrayBasedStringList( array_merge( $this->getAllowedWords(), $newAllowedWordsArray ) );
	}

	private function hasDeniedWords( string $text, bool $ignoreAllowedWords ): bool {
		$deniedMatches = $this->getMatches( $text, $this->getDeniedWords() );

		if ( $ignoreAllowedWords ) {
			$allowedMatches = $this->getMatches( $text, $this->getAllowedWords() );

			if ( count( $allowedMatches ) > 0 ) {
				return $this->hasDeniedWordNotMatchingAllowedWords( $deniedMatches, $allowedMatches );
			}

		}

		return count( $deniedMatches ) > 0;
	}

	private function getMatches( string $text, array $wordArray ): array {
		$matches = [];
		preg_match_all( $this->composeRegex( $wordArray ), $text, $matches );
		return $matches[0];
	}

	private function hasDeniedWordNotMatchingAllowedWords( array $deniedMatches, array $allowedMatches ): bool {
		return count(
				array_udiff(
					$deniedMatches,
					$allowedMatches,
					function ( $deniedMatch, $allowedMatch ) {
						return (int)!preg_match( $this->composeRegex( [ $deniedMatch ] ), $allowedMatch );
					}
				)
			) > 0;
	}

	private function wordMatchesAllowedWords( string $word ): bool {
		return in_array( strtolower( $word ), array_map( 'strtolower', $this->getAllowedWords() ) );
	}

	private function hasUrls( string $text, bool $testWithDNS, bool $ignoreAllowedWords ): bool {
		// check for obvious URLs
		if ( preg_match( '|https?://www\.[a-z\.0-9]+|i', $text ) || preg_match( '|www\.[a-z\.0-9]+|i', $text ) ) {
			return true;
		}

		// check for non-obvious URLs with dns lookup
		if ( $testWithDNS ) {
			$possibleDomainNames = $this->extractPossibleDomainNames( $text );
			foreach ( $possibleDomainNames as $domainName ) {
				if ( !( $ignoreAllowedWords && $this->wordMatchesAllowedWords( $domainName ) ) && $this->isExistingDomain(
						$domainName
					) ) {
					return true;
				}
			}
		}

		return false;
	}

	private function extractPossibleDomainNames( string $text ): array {
		preg_match_all( '|[a-z\.0-9]+\.[a-z]{2,6}|i', $text, $possibleDomainNames );
		return $possibleDomainNames[0];
	}

	private function isExistingDomain( string $domainName ): bool {
		if ( filter_var( 'http://' . $domainName, FILTER_VALIDATE_URL ) === false ) {
			return false;
		}
		return checkdnsrr( $domainName, 'A' );
	}

	private function composeRegex( array $wordArray ): string {
		$quotedWords = array_map(
			static function ( string $word ) {
				return str_replace( ' ', '\\s*', preg_quote( trim( $word ), '#' ) );
			},
			$wordArray
		);
		return '#(.*?)(' . implode( '|', $quotedWords ) . ')#i';
	}

}
