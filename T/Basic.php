<?php
namespace Df\Geo\T;
use Geocoder\Model\Address as A;
use Geocoder\Model\AddressCollection as AA;
use Geocoder\Provider\GoogleMaps as API;
use Ivory\HttpAdapter\CurlHttpAdapter as Adapter;
use Ivory\HttpAdapter\HttpAdapterInterface as IAdapter;
// 2017-04-24
final class Basic extends TestCase {
	/** @test 2017-04-24 */
	function t01() {
		/**
		 * 2017-04-24
		 * https://github.com/geocoder-php/Geocoder/blob/v3.3.0/README.md
		 * @param IAdapter $adapter An HTTP adapter
		 * @param string $locale  A locale (optional)
		 * @param string $region  Region biasing (optional)
		 * @param bool $useSsl  Whether to use an SSL connection (optional)
		 * @param string $apiKey  Google Geocoding API key (optional)
		 * @var API $api
		 */
		$api = new API(new Adapter
			// 2017-04-24
			// PHPDoc: «A locale (optional)».
			,df_locale()
			/**         
			 * 2017-04-24
			 * PHPDoc: «Region biasing (optional)».
			 *
			 * Google Maps API Reference:
			 * «The region code, specified as a ccTLD ("top-level domain") two-character value.
			 * This parameter will only influence, not fully restrict, results from the geocoder.»
			 * https://developers.google.com/maps/documentation/geocoding/intro#geocoding
			 *
			 * «Region Biasing
			 * In a geocoding request, you can instruct the Geocoding service
			 * to return results biased to a particular region by using the region parameter.
			 * This parameter takes a ccTLD (country code top-level domain) argument
			 * specifying the region bias.
			 * Most ccTLD codes are identical to ISO 3166-1 codes, with some notable exceptions.
			 *
			 * For example, the United Kingdom's ccTLD is "uk" (.co.uk) while its ISO 3166-1 code is "gb"
			 * (technically for the entity of "The United Kingdom of Great Britain and Northern Ireland").
			 * Geocoding results can be biased for every domain
			 * in which the main Google Maps application is officially launched.
			 * Note that biasing only prefers results for a specific domain;
			 * if more relevant results exist outside of this domain, they may be included.
			 *
			 * For example, a geocode for "Toledo" returns this result,
			 * as the default domain for the Google Maps Geocoding API is set to the United States.
			 * Request: https://maps.googleapis.com/maps/api/geocode/json?address=Toledo&key=YOUR_API_KEY
			 * Response:
			 *	{
			 *		"results": [
			 *			{<...>, "formatted_address": "Toledo, OH, USA", <...>},
			 *			{<...>, "formatted_address": "Toledo, OR, USA", <...>},
			 *			{<...>, "formatted_address": "Toledo, IA, USA", <...>},
			 *			{<...>, "formatted_address": "Toledo, WA 98591, USA", <...>}
			 *		],
			 *		"status": "OK"
			 *	}
			 * A geocoding request for "Toledo" with region=es (Spain) will return the Spanish city.
			 * Request: https://maps.googleapis.com/maps/api/geocode/json?address=Toledo&region=es&key=YOUR_API_KEY
			 * Response:
			 *	{
			 *		"results": [
			 *			{<...>, "formatted_address": "Toledo, Toledo, Spain", <...>}
			 *		],
			 *		"status": "OK"
			 *	}
			 * https://developers.google.com/maps/documentation/geocoding/intro#RegionCodes
			 */
			//
			,null
			// 2017-04-24
			// PHPDoc: «Whether to use an SSL connection (optional)».
			// Google Maps требует значение true.
			,true
			// 2017-04-24
			// Google Maps API Reference: «Your application's API key.
			// This key identifies your application for purposes of quota management.»
			// https://developers.google.com/maps/documentation/geocoding/intro#geocoding
			// «How to generate a key for the Google Maps Geocoding API?» https://mage2.pro/t/3828
			,'AIzaSyBj8bPt0PeSxcgPW8vTfNI2xKdhkHCUYuc'
		);
		// 2017-04-24
		// Google Maps API Reference: «The street address that you want to geocode,
		// in the format used by the national postal service of the country concerned.
		// Additional address elements such as business names and unit, suite or floor numbers
		// should be avoided. Please refer to the FAQ for additional guidance.»
		// https://developers.google.com/maps/documentation/geocoding/intro#geocoding
		/** @var AA $aa */
		$aa = $api->geocode('Av. Lúcio Costa, 3150 - Barra da Tijuca, Rio de Janeiro - RJ, 22630-010');
		/**
		 * 2017-04-24
		 * Обращение к несуществующим элементам приводит к исключительной ситуации:
		 * @see \Geocoder\Model\AddressCollection::first()
		 * @see \Geocoder\Model\AddressCollection::get()
		 */
		if (count($aa)) {
			/** @var A $a */
			$a = $aa->first();
			echo df_dump([
				// 2017-04-24 In my case: «BR».
				'country' => $a->getCountryCode()
				// 2017-04-24 In my case: «null».
				,'locality' => $a->getLocality()
				// 2017-04-24 In my case: «22630-010».
				,'postalCode' => $a->getPostalCode()
				// 2017-04-24 In my case: «Avenida Lúcio Costa».
				,'streetName' => $a->getStreetName()
				// 2017-04-24 In my case: «3150».
				,'streetNumber' => $a->getStreetNumber()
			]);
		}
		$a = $aa->first();
		xdebug_break();
	}
}