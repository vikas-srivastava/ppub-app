<?php

namespace App\Services\Packt;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\PacktApiException;
use Illuminate\Support\Facades\Storage;

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

    public function getProducts($limit = 9999)
    {   
        $apiProductsURI = $this->uri. 'products';
        $response = Http::acceptJson()->get($apiProductsURI, ['token' => $this->apiToken]);
        $responseData =  json_decode($response->body());

        if(isset($responseData->errorMessage)) {
            throw new PacktApiException($responseData->errorMessage);
        } else {
            Cache::put('products', $responseData->products, now()->addDays(7));
            return $responseData->products; 
        }  
    }

    public function getProduct($id)
    {   
        $apiProductsURI = $this->uri. 'products' . '/' . $id;
        $response = Http::acceptJson()->get($apiProductsURI, ['token' => $this->apiToken]);
        $responseData =  json_decode($response->body());
        
        if(isset($responseData->errorMessage)) {
            throw new PacktApiException($responseData->errorMessage);
        } else {
            $productCacheId = 'product'. '-' . $id;
            Cache::put($productCacheId, $responseData, now()->addDays(7));
            return $responseData; 
        }  
    }

    public function getPrices($productId)
    {
        $productPricesApiCacheId = 'product-prices' . '-' . $productId;

        $apiPriceURI = $this->uri. 'products/' . $productId . '/price';
        $response = Http::acceptJson()->get($apiPriceURI, ['token' => $this->apiToken]);
        $responseData =  json_decode($response->body());

        if(isset($responseData->errorMessage)) {
            throw new PacktApiException($responseData->errorMessage);
        } else {
            Cache::put($productPricesApiCacheId, $responseData->prices, now()->addDays(7));
            return $responseData->prices;
        } 
    }

    public function getImages($productId)
    {
        $productImages = array();
        $productImageApiCacheId = 'product-images' . '-' . $productId;
        $largeImagePath = 'product-images/' . $productId . '-large.jpeg';
        $smallImagePath = 'product-images/' . $productId . '-small.jpeg';

        $apiImageLargeURI = $this->uri. 'products/' . $productId . '/cover/large';
        $apiImageSmallURI = $this->uri. 'products/' . $productId . '/cover/small';

        $largeImageResponse = Http::accept('image/jpeg')->get($apiImageLargeURI, ['token' => $this->apiToken]);
        $smallImageResponse = Http::accept('image/jpeg')->get($apiImageSmallURI, ['token' => $this->apiToken]);

        Storage::disk('public')->put($largeImagePath, $largeImageResponse->body());
        Storage::disk('public')->put($smallImagePath, $smallImageResponse->body());
        
        $productImages['large'] = $largeImagePath;
        $productImages['small'] = $smallImagePath;

        Cache::put($productImageApiCacheId, $productImages, now()->addDays(7));

        return $productImages;
    }

}