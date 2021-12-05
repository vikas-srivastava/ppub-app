<?php

namespace App\Services\Packt;

use Illuminate\Support\Facades\Http;
use App\Exceptions\PacktApiException;

class ApiClient {

    protected $uri;
    protected $apiToken;

    public function __construct(string $uri, string $apiToken)
    {
        $this->uri = $uri;
        $this->apiToken = $apiToken;
    }
    
    public function testApi() {

        $apiTestURI = $this->uri. 'test';
        $response = Http::acceptJson()->get($apiTestURI, ['token' => $this->apiToken]);

        if($response->successful()) {
            $responseData =  json_decode($response->body());

            if($responseData->system === 'OK' && $responseData->token === 'OK') {
                return 'OK';
            }

            if(isset($responseData->errorMessage)) {
                throw new PacktApiException($responseData->errorMessage);
            }
        }
    }

    public function getProducts($limit = 10000)
    {   
        $apiProductsURI = $this->uri. 'products';
        $response = Http::acceptJson()->get($apiProductsURI, ['token' => $this->apiToken, 'limit' => $limit]);
        $responseData =  json_decode($response->body());

        if(isset($responseData->errorMessage)) {
            throw new PacktApiException($responseData->errorMessage);
        }

        return $responseData->products;
    }

    public function getPrice($productId)
    {
        $apiPriceURI = $this->uri. 'products/' . $productId . '/price';
        $response = Http::acceptJson()->get($apiPriceURI, ['token' => $this->apiToken]);
        $responseData =  json_decode($response->body());

        if(isset($responseData->errorMessage)) {
            throw new PacktApiException($responseData->errorMessage);
        }

        return $responseData->prices;
    }

    public function getImages($productId)
    {
        $productImages = array();

        $apiImageLargeURI = $this->uri. 'products/' . $productId . '/cover/large';
        $apiImageSmallURI = $this->uri. 'products/' . $productId . '/cover/small';

        $largeImage = Http::acceptJson()->get($apiImageLargeURI, ['token' => $this->apiToken]);
        $smallImage = Http::acceptJson()->get($apiImageSmallURI, ['token' => $this->apiToken]);
        
        $productImages['large'] = $largeImage;
        $productImages['small'] = $smallImage;
 
        return $productImages;
    }

}