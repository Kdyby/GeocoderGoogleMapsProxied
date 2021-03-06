<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Geocoder\Provider\ProxiedGoogleMaps;

use Ivory\HttpAdapter\HttpAdapterInterface;

class ProxiedGoogleMapsProvider extends \Geocoder\Provider\GoogleMaps
{

	/**
	 * @var string
	 */
	private $proxy;

	public function __construct(HttpAdapterInterface $adapter, $proxy, $locale = NULL, $region = NULL, $apiKey = NULL)
	{
		parent::__construct($adapter, $locale, $region, TRUE, $apiKey);
		$this->proxy = $proxy;
	}

	protected function buildQuery($query)
	{
		return parent::buildQuery($this->proxy . '?' . parse_url($query, PHP_URL_QUERY));
	}

	public function getName()
	{
		return parent::getName() . '_proxied';
	}

}
