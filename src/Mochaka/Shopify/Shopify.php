<?php 

namespace Mochaka\Shopify;
use Guzzle\Http\Client as Client;

class Shopify {

	/**
     * Guzzle Client
     *
     * @var object
     */
    protected $client;

    public function __construct()
    {
    	
		$this->client = new \Client([
		    'base_url' => ['https://{key}:{password}@{url}/admin', ['url' => Config::get('package::url'), 'key' => Config::get('package::apikey'), 'password' => Config::get('package::password')]],
		    'defaults' => [
		        'headers' => ['Content-Type' => 'application/json'],
		    ]
		]);
    }

    public function test()
    {

    }

    private function makeRequest($page, $data)
    {

    }

}