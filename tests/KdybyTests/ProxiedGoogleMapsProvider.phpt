<?php

/**
 * Test: Kdyby\Geocoder\Provider\ProxiedGoogleMaps\ProxiedGoogleMapsProvider.
 * @testCase
 */

namespace KdybyTests\Geocoder\Provider\ProxiedGoogleMaps;

use Kdyby;
use Kdyby\Geocoder\Provider\ProxiedGoogleMaps\ProxiedGoogleMapsProvider;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ProxiedGoogleMapsProviderTest extends Tester\TestCase
{

	public function testGeocode()
	{
		/** @var \Mockery\Mock|\Ivory\HttpAdapter\HttpAdapterInterface $adapter */
		$adapter = \Mockery::mock(\Ivory\HttpAdapter\HttpAdapterInterface::class)->shouldDeferMissing();
		$adapter->shouldReceive('get')->once()->andReturnUsing(function () {
			$response = \Mockery::mock(\Ivory\HttpAdapter\Message\ResponseInterface::class)->shouldDeferMissing();
			$response->shouldReceive('getBody')->andReturn(file_get_contents(__DIR__ . '/data/soukenicka_5_brno.json'));
			return $response;
		})->withArgs(['https://geocoder.kdyby.org/?address=Soukenick%C3%A1%205%2C%20Brno&language=cs_CZ&region=CZ&key=nemam']);

		$provider = new ProxiedGoogleMapsProvider($adapter, 'https://geocoder.kdyby.org/', 'cs_CZ', 'CZ', 'nemam');
		$result = $provider->geocode('Soukenická 5, Brno');

		$address = $result->first();
		Assert::same(49.1881705, $address->getLatitude());
		Assert::same(16.6049561, $address->getLongitude());
	}

}

(new ProxiedGoogleMapsProviderTest())->run();
