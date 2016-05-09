<?php

namespace Mochaka\Shopify;

use Httpful\Http;
use Httpful\Httpful;
use Httpful\Request;

class Shopify
{

    /**
     * base url
     *
     * @var string
     */
    protected $url;

    /**
     * __construct to init the guzzle client
     *
     * @param string $domain
     * @param string $key
     * @param string $password
     */
    public function __construct($domain, $key, $password)
    {
        $this->url = "https://" . $key . ":" . $password . "@" . $domain . "/admin/";
    }

    /**
     * send off the request to Shopify, encoding the data as JSON
     *
     * @param  string $method
     * @param  string $page
     * @param  array $data
     *
     * @return array
     */
    public function makeRequest($method, $page, $data = [])
    {
        $url = $this->url . "/" . $page;

        if ($method == 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        $r = Request::init($method)->uri($url);

        if ($data && $method != 'GET') {
            $r->body(json_encode($data), 'application/json');
        }
        $r = $r->send();

        if ($r->code !== 200) {
            return [
                'error' => $r->body,
                'url' => $url,
                'request' => '',
                'status' => $r->code,
                'response' => $r->body
            ];
        } else {
            return json_decode(json_encode($r->body), true);
        }

    }

    /**
     * returns a count of products
     *
     * @return array
     */
    public function getProductsCount()
    {
        return $this->makeRequest('GET', 'products/count.json');
    }

    /**
     * returns a list of products, depending on the input data
     *
     * @param  array $data
     *
     * @return array
     */
    public function getProducts($data)
    {
        return $this->makeRequest('GET', 'products.json', $data);
    }

    /**
     * returns product information by id
     *
     * @param  int $productId
     *
     * @return array
     */
    public function getProductById($productId)
    {
        $data = $this->makeRequest('GET', 'products/' . $productId . '.json');
        return isset($data['product']) ? $data['product'] : $data;
    }

    /**
     * returns variant information by id
     *
     * @param  int $productId
     *
     * @return array
     */
    public function getVariantById($productId)
    {
        $data = $this->makeRequest('GET', 'variants/' . $productId . '.json');
        return isset($data['variant']) ? $data['variant'] : $data;
    }

    /**
     * creates a product on shopify
     *
     * @param  array $data
     *
     * @return array
     */
    public function createProduct($data)
    {
        $d['product'] = (!isset($data['product'])) ? $data : $data['product'];
        return $this->makeRequest('POST', 'products.json', $d);
    }

    /**
     * updates a product by id
     *
     * @param  int $productId
     * @param  array $data
     *
     * @return array
     */
    public function updateProduct($productId, $data)
    {
        $d['product'] = (!isset($data['product'])) ? $data : $data['product'];
        return $this->makeRequest('PUT', 'products/' . $productId . '.json', $d);
    }

    /**
     * Delete's a product from shopify
     *
     * @param  int $productId
     *
     * @return array
     */
    public function deleteProduct($productId)
    {
        return $this->makeRequest('DELETE', 'products/' . $productId . '.json');
    }

    /**
     * updates a specific variant by id
     *
     * @param  int $variantId
     * @param  array $data
     *
     * @return array
     */
    public function updateVariant($variantId, $data)
    {
        $data['id'] = $variantId;
        $d['variant'] = (!isset($data['variant'])) ? $data : $data['variant'];
        return $this->makeRequest('PUT', 'variants/' . $variantId . '.json', $d);
    }

    /**
     * creates a variant for the specific shopify id
     *
     * @param $shopifyId
     * @param $data
     *
     * @return array
     */
    public function createVariant($shopifyId, $data)
    {
        $d['variant'] = (!isset($data['variant'])) ? $data : $data['variant'];
        return $this->makeRequest('POST', 'products/' . $shopifyId . '/variants.json', $d);
    }

    /**
     * Delete's a variant from shopify
     *
     * @param  int $productId
     * @param  int $variantId
     *
     * @return array
     */
    public function deleteVariant($productId, $variantId)
    {
        return $this->makeRequest('DELETE', 'products/' . $productId . '/variants/' . $variantId . '.json');
    }

    /**
     * get a list of webhooks
     *
     * @return array
     */
    public function getWebhooks()
    {
        return $this->makeRequest('GET', 'webhooks.json');
    }

    /**
     * create a webhook
     *
     * @param  array $data
     *
     * @return array
     */
    public function createWebhook($data)
    {
        $d['webhook'] = (!isset($data['webhook'])) ? $data : $data['webhook'];
        return $this->makeRequest('POST', 'webhooks.json', $d);
    }

    /**
     * get a list of all customers in shopify
     *
     * @return array
     */
    public function getAllCustomers()
    {
        return $this->makeRequest('GET', 'customers.json');
    }

    /**
     * creates an order on shopify
     *
     * @param $data
     *
     * @return array
     */
    public function createOrder($data)
    {
        $d['order'] = (!isset($data['order'])) ? $data : $data['order'];
        return $this->makeRequest('POST', 'orders.json', $d);
    }

    /**
     * Receives a list of orders
     *
     * @param $data
     *
     * @return array
     */
    public function getOrders($data = [])
    {
        return $this->makeRequest('GET', 'orders.json', $data);
    }

    /**
     * Receives a single order
     *
     * @param int $id
     *
     * @return array
     */
    public function getOrder($id)
    {
        return $this->makeRequest('GET', 'orders/' . $id . '.json');
    }
}
