<?php 

namespace Mochaka\Shopify;

use Config;
use Guzzle\Http\Client;


class Shopify {

	/**
     * Guzzle Client
     *
     * @var object
     */
    protected $client;

    protected $url;

    public function __construct($domain, $key, $password)
    {
        $this->url = "https://".$key.":".$password."@".$domain."/admin/";
        print "<pre>";
		$this->client = new Client(
		    $this->url,
		    ['defaults' => [
		        'headers' => ['Content-Type' => 'application/json'],
                'auth'    => [$key, $password],
		    ]]
		);
    }

    private function makeRequest($method, $page, $data = array())
    {
        $data = json_encode($data);
        return $this->client->createRequest($method, $page, null, $data)->send()->json();
    }

    public function getProductsCount()
    {
        return $this->makeRequest('GET', 'products/count.json');
    }

    public function getProductById($productId)
    {
        return $this->makeRequest('GET', 'products/'.$productId.'.json')['product'];
    }

    public function createProduct($data)
    {
        $d['product'] = (!isset($data['product'])) ? $data : $data['product'];
        return $this->makeRequest('POST', 'products.json', $d);       
    }

    public function updateProduct($productId, $data)
    {
        $d['product'] = (!isset($data['product'])) ? $data : $data['product'];
        return $this->makeRequest('PUT', 'products/'.$productId.'.json', $d);
    }

    public function updateVariant($variantId, $data)
    {
        $d['variant'] = (!isset($data['variant'])) ? $data : $data['variant'];
        return $this->makeRequest('PUT', 'variants/'.$variantId.'.json', $d);
    }
}