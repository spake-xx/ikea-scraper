<?php

namespace App\Providers;

class IkeaProvider
{
    public function __construct() {

    }


    public function getOkazje(array $queries): array {
        $allProducts = [];
        foreach($queries as $query) {
            $queryProducts = $this->getQuery($query);
            foreach($queryProducts as $product) {
                if(!in_array($product['id'], array_column($allProducts, 'id'))) {
                    $allProducts[] = $product;
                }
            }
        }

        return $allProducts;
    }

    public function getQuery(string $query): array
    {
        $parameters = [
            'stores' => 306,
            'size' => 64,
            'search' => $query
        ];
        $result = $this->getProducts($parameters, 1);
        $totalPages = $result['totalPages'];
        $products = $result['content'];
        for ($i = 0; $i < $totalPages; $i++) {
            $result = $this->getProducts($parameters, $i);
            $products = array_merge($products, $result['content']);
        }

        return $products;
    }

    public function getProducts($parameters, $page): array {
        $url = 'https://web-api.ikea.com/circular/circular-asis/offers/public/pl/pl?page='.$page;
        $fullUrl = $url .'&'. http_build_query($parameters);
        // retrieve json using guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $fullUrl);
        $response = $response->getBody()->getContents();
        $serializer = new \JMS\Serializer\SerializerBuilder();
        $serializer = $serializer->build();
        $response = $serializer->deserialize($response, 'array', 'json');
        return $response;
    }
}