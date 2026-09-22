<?php

declare( strict_types = 1 );

namespace WMDE\FunValidators\Data;

class IbanFormats {
	// phpcs:disable
	/**
	 * IBAN country specific formats, stripped from Symfony Vslidator https://github.com/symfony/validator.
	 *
	 * The first 2 characters from an IBAN format are the two-character ISO country code.
	 * The following 2 characters represent the check digits calculated from the rest of the IBAN characters.
	 * The rest are up to thirty alphanumeric characters for
	 * a BBAN (Basic Bank Account Number) which has a fixed length per country and,
	 * included within it, a bank identifier with a fixed position and a fixed length per country
	 *
	 * @see Resources/bin/sync-iban-formats.php
	 * @see https://www.swift.com/swift-resource/11971/download?language=en
	 * @see https://en.wikipedia.org/wiki/International_Bank_Account_Number
	 */
	public const array REGEXES = [
		// auto-generated
		'AD' => 'AD\d{2}\d{4}\d{4}[\dA-Z]{12}', // Andorra
		'AE' => 'AE\d{2}\d{3}\d{16}', // United Arab Emirates (The)
		'AL' => 'AL\d{2}\d{8}[\dA-Z]{16}', // Albania
		'AO' => 'AO\d{2}\d{21}', // Angola
		'AT' => 'AT\d{2}\d{5}\d{11}', // Austria
		'AX' => 'FI\d{2}\d{3}\d{11}', // Finland
		'AZ' => 'AZ\d{2}[A-Z]{4}[\dA-Z]{20}', // Azerbaijan
		'BA' => 'BA\d{2}\d{3}\d{3}\d{8}\d{2}', // Bosnia and Herzegovina
		'BE' => 'BE\d{2}\d{3}\d{7}\d{2}', // Belgium
		'BF' => 'BF\d{2}[\dA-Z]{2}\d{22}', // Burkina Faso
		'BG' => 'BG\d{2}[A-Z]{4}\d{4}\d{2}[\dA-Z]{8}', // Bulgaria
		'BH' => 'BH\d{2}[A-Z]{4}[\dA-Z]{14}', // Bahrain
		'BI' => 'BI\d{2}\d{5}\d{5}\d{11}\d{2}', // Burundi
		'BJ' => 'BJ\d{2}[\dA-Z]{2}\d{22}', // Benin
		'BL' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'BR' => 'BR\d{2}\d{8}\d{5}\d{10}[A-Z]{1}[\dA-Z]{1}', // Brazil
		'BY' => 'BY\d{2}[\dA-Z]{4}\d{4}[\dA-Z]{16}', // Republic of Belarus
		'CF' => 'CF\d{2}\d{23}', // Central African Republic
		'CG' => 'CG\d{2}\d{23}', // Congo, Republic of the
		'CH' => 'CH\d{2}\d{5}[\dA-Z]{12}', // Switzerland
		'CI' => 'CI\d{2}[A-Z]{1}\d{23}', // Côte d'Ivoire
		'CM' => 'CM\d{2}\d{23}', // Cameroon
		'CR' => 'CR\d{2}\d{4}\d{14}', // Costa Rica
		'CV' => 'CV\d{2}\d{21}', // Cabo Verde
		'CY' => 'CY\d{2}\d{3}\d{5}[\dA-Z]{16}', // Cyprus
		'CZ' => 'CZ\d{2}\d{4}\d{6}\d{10}', // Czechia
		'DE' => 'DE\d{2}\d{8}\d{10}', // Germany
		'DJ' => 'DJ\d{2}\d{5}\d{5}\d{11}\d{2}', // Djibouti
		'DK' => 'DK\d{2}\d{4}\d{9}\d{1}', // Denmark
		'DO' => 'DO\d{2}[\dA-Z]{4}\d{20}', // Dominican Republic
		'DZ' => 'DZ\d{2}\d{22}', // Algeria
		'EE' => 'EE\d{2}\d{2}\d{14}', // Estonia
		'EG' => 'EG\d{2}\d{4}\d{4}\d{17}', // Egypt
		'ES' => 'ES\d{2}\d{4}\d{4}\d{1}\d{1}\d{10}', // Spain
		'FI' => 'FI\d{2}\d{3}\d{11}', // Finland
		'FK' => 'FK\d{2}[A-Z]{2}\d{12}', // Falkland Islands
		'FO' => 'FO\d{2}\d{4}\d{9}\d{1}', // Faroe Islands
		'FR' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'GA' => 'GA\d{2}\d{23}', // Gabon
		'GB' => 'GB\d{2}[A-Z]{4}\d{6}\d{8}', // United Kingdom
		'GE' => 'GE\d{2}[A-Z]{2}\d{16}', // Georgia
		'GF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'GG' => 'GB\d{2}[A-Z]{4}\d{6}\d{8}', // United Kingdom
		'GI' => 'GI\d{2}[A-Z]{4}[\dA-Z]{15}', // Gibraltar
		'GL' => 'GL\d{2}\d{4}\d{9}\d{1}', // Greenland
		'GP' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'GQ' => 'GQ\d{2}\d{23}', // Equatorial Guinea
		'GR' => 'GR\d{2}\d{3}\d{4}[\dA-Z]{16}', // Greece
		'GT' => 'GT\d{2}[\dA-Z]{4}[\dA-Z]{20}', // Guatemala
		'GW' => 'GW\d{2}[\dA-Z]{2}\d{19}', // Guinea-Bissau
		'HN' => 'HN\d{2}[A-Z]{4}\d{20}', // Honduras
		'HR' => 'HR\d{2}\d{7}\d{10}', // Croatia
		'HU' => 'HU\d{2}\d{3}\d{4}\d{1}\d{15}\d{1}', // Hungary
		'IE' => 'IE\d{2}[A-Z]{4}\d{6}\d{8}', // Ireland
		'IL' => 'IL\d{2}\d{3}\d{3}\d{13}', // Israel
		'IM' => 'GB\d{2}[A-Z]{4}\d{6}\d{8}', // United Kingdom
		'IQ' => 'IQ\d{2}[A-Z]{4}\d{3}\d{12}', // Iraq
		'IR' => 'IR\d{2}\d{22}', // Iran
		'IS' => 'IS\d{2}\d{4}\d{2}\d{6}\d{10}', // Iceland
		'IT' => 'IT\d{2}[A-Z]{1}\d{5}\d{5}[\dA-Z]{12}', // Italy
		'JE' => 'GB\d{2}[A-Z]{4}\d{6}\d{8}', // United Kingdom
		'JO' => 'JO\d{2}[A-Z]{4}\d{4}[\dA-Z]{18}', // Jordan
		'KM' => 'KM\d{2}\d{23}', // Comoros
		'KW' => 'KW\d{2}[A-Z]{4}[\dA-Z]{22}', // Kuwait
		'KZ' => 'KZ\d{2}\d{3}[\dA-Z]{13}', // Kazakhstan
		'LB' => 'LB\d{2}\d{4}[\dA-Z]{20}', // Lebanon
		'LC' => 'LC\d{2}[A-Z]{4}[\dA-Z]{24}', // Saint Lucia
		'LI' => 'LI\d{2}\d{5}[\dA-Z]{12}', // Liechtenstein
		'LT' => 'LT\d{2}\d{5}\d{11}', // Lithuania
		'LU' => 'LU\d{2}\d{3}[\dA-Z]{13}', // Luxembourg
		'LV' => 'LV\d{2}[A-Z]{4}[\dA-Z]{13}', // Latvia
		'LY' => 'LY\d{2}\d{3}\d{3}\d{15}', // Libya
		'MA' => 'MA\d{2}\d{24}', // Morocco
		'MC' => 'MC\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Monaco
		'MD' => 'MD\d{2}[\dA-Z]{2}[\dA-Z]{18}', // Moldova
		'ME' => 'ME\d{2}\d{3}\d{13}\d{2}', // Montenegro
		'MF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'MG' => 'MG\d{2}\d{23}', // Madagascar
		'MK' => 'MK\d{2}\d{3}[\dA-Z]{10}\d{2}', // Macedonia
		'ML' => 'ML\d{2}[\dA-Z]{2}\d{22}', // Mali
		'MN' => 'MN\d{2}\d{4}\d{12}', // Mongolia
		'MQ' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'MR' => 'MR\d{2}\d{5}\d{5}\d{11}\d{2}', // Mauritania
		'MT' => 'MT\d{2}[A-Z]{4}\d{5}[\dA-Z]{18}', // Malta
		'MU' => 'MU\d{2}[A-Z]{4}\d{2}\d{2}\d{12}\d{3}[A-Z]{3}', // Mauritius
		'MZ' => 'MZ\d{2}\d{21}', // Mozambique
		'NC' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'NE' => 'NE\d{2}[A-Z]{2}\d{22}', // Niger
		'NI' => 'NI\d{2}[A-Z]{4}\d{20}', // Nicaragua
		'NL' => 'NL\d{2}[A-Z]{4}\d{10}', // Netherlands (The)
		'NO' => 'NO\d{2}\d{4}\d{6}\d{1}', // Norway
		'OM' => 'OM\d{2}\d{3}[\dA-Z]{16}', // Oman
		'PF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'PK' => 'PK\d{2}[A-Z]{4}[\dA-Z]{16}', // Pakistan
		'PL' => 'PL\d{2}\d{8}\d{16}', // Poland
		'PM' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'PS' => 'PS\d{2}[A-Z]{4}[\dA-Z]{21}', // Palestine, State of
		'PT' => 'PT\d{2}\d{4}\d{4}\d{11}\d{2}', // Portugal
		'QA' => 'QA\d{2}[A-Z]{4}[\dA-Z]{21}', // Qatar
		'RE' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'RO' => 'RO\d{2}[A-Z]{4}[\dA-Z]{16}', // Romania
		'RS' => 'RS\d{2}\d{3}\d{13}\d{2}', // Serbia
		'RU' => 'RU\d{2}\d{9}\d{5}[\dA-Z]{15}', // Russia
		'SA' => 'SA\d{2}\d{2}[\dA-Z]{18}', // Saudi Arabia
		'SC' => 'SC\d{2}[A-Z]{4}\d{2}\d{2}\d{16}[A-Z]{3}', // Seychelles
		'SD' => 'SD\d{2}\d{2}\d{12}', // Sudan
		'SE' => 'SE\d{2}\d{3}\d{16}\d{1}', // Sweden
		'SI' => 'SI\d{2}\d{5}\d{8}\d{2}', // Slovenia
		'SK' => 'SK\d{2}\d{4}\d{6}\d{10}', // Slovakia
		'SM' => 'SM\d{2}[A-Z]{1}\d{5}\d{5}[\dA-Z]{12}', // San Marino
		'SN' => 'SN\d{2}[A-Z]{2}\d{22}', // Senegal
		'SO' => 'SO\d{2}\d{4}\d{3}\d{12}', // Somalia
		'ST' => 'ST\d{2}\d{8}\d{11}\d{2}', // Sao Tome and Principe
		'SV' => 'SV\d{2}[A-Z]{4}\d{20}', // El Salvador
		'TD' => 'TD\d{2}\d{23}', // Chad
		'TF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'TG' => 'TG\d{2}[A-Z]{2}\d{22}', // Togo
		'TL' => 'TL\d{2}\d{3}\d{14}\d{2}', // Timor-Leste
		'TN' => 'TN\d{2}\d{2}\d{3}\d{13}\d{2}', // Tunisia
		'TR' => 'TR\d{2}\d{5}\d{1}[\dA-Z]{16}', // Turkey
		'UA' => 'UA\d{2}\d{6}[\dA-Z]{19}', // Ukraine
		'VA' => 'VA\d{2}\d{3}\d{15}', // Vatican City State
		'VG' => 'VG\d{2}[A-Z]{4}\d{16}', // Virgin Islands
		'WF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
		'XK' => 'XK\d{2}\d{4}\d{10}\d{2}', // Kosovo
		'YE' => 'YE\d{2}[A-Z]{4}\d{4}[\dA-Z]{18}', // Yemen
		'YT' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
	];
	// phpcs:enable
}
