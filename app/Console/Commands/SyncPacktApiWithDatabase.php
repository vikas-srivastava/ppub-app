<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\Packt\ApiClient;

use App\Models\Author;
use App\Models\Concept;
use App\Models\Category;
use App\Models\Jobrole;
use App\Models\Language;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tool;
use App\Models\User;
use App\Models\Vendor;

class SyncPacktApiWithDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packapi:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch pack api data and refresh local database.';

    protected $apiClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ApiClient $apiClient)
    {
        parent::__construct();
        $this->apiClient = $apiClient;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (Cache::has('products')) {
            $products = Cache::get('products');
            var_dump('from cache');
        } else {
            $products = $this->apiClient->getProducts();
            var_dump('from live');
        }

        foreach ($products as $product) {

            // Save Product
            $productApiCacheId = 'product' . '-' . $product->id;
            if (Cache::has($productApiCacheId)) {
                $apiProduct = Cache::get($productApiCacheId);
                var_dump('product details from cache');
            } else {
                $apiProduct = $this->apiClient->getProduct($product->id);
                var_dump('product details from live');
            }

            $productExistLocally = Product::where('isbn13', '=', $product->isbn13)->first();
            if ($productExistLocally) {
                $localProduct = $productExistLocally;
                var_dump('old product');
            } else {
                $localProduct = new Product();
                var_dump('new product');
            }

            $localProduct->packtId = $apiProduct->id;
            $localProduct->isbn13 = $apiProduct->isbn13;
            $localProduct->isbn10 = $apiProduct->isbn10;

            $localProduct->title = $apiProduct->title;
            $localProduct->product_type = $apiProduct->product_type;
            $localProduct->tagline = $apiProduct->tagline;
            $localProduct->pages = $apiProduct->pages;
            $localProduct->publication_date = $product->publication_date;
            $localProduct->length = $apiProduct->length;

            $localProduct->learn = $apiProduct->learn;
            $localProduct->features = $apiProduct->features;
            $localProduct->description = $apiProduct->description;
            $localProduct->url = $apiProduct->url;

            $localProduct->save();

            //Save Image
            $productImageApiCacheId = 'product-images' . '-' . $product->id;

            if (Cache::has($productImageApiCacheId)) {
                $apiProductImage = Cache::get($productImageApiCacheId);
                var_dump('product images from cache');
            } else {
                $apiProductImage = $this->apiClient->getImages($product->id);
                var_dump('product images from live');
            }

            $localProductImages = new ProductImage();
            $localProductImages->large_image_url = $apiProductImage['large'];
            $localProductImages->small_image_url = $apiProductImage['small'];
            $localProductImages->product_id = $localProduct->id;
            $localProductImages->save();

            // Save Prices

            $productPricesApiCacheId = 'product-prices' . '-' . $product->id;
            if (Cache::has($productPricesApiCacheId)) {
                $apiProductPrices = Cache::get($productPricesApiCacheId);
                var_dump('product prices from cache');
            } else {
                $apiProductPrices = $this->apiClient->getPrices($product->id);
                var_dump('product prices from live');
            }

            // save print price
            if (isset($apiProductPrices->print)) {
                $pricePrint = Price::where([
                    'print' => 'print',
                    'product_id'  => $localProduct->id
                ])->first();

                if (!$pricePrint) {
                    $pricePrint = new Price();
                }
                $pricePrint->print = 'print';
                $pricePrint->USD = $apiProductPrices->print->USD;
                $pricePrint->GBP = $apiProductPrices->print->GBP;
                $pricePrint->EUR = $apiProductPrices->print->EUR;
                $pricePrint->AUD = $apiProductPrices->print->AUD;
                $pricePrint->INR = $apiProductPrices->print->INR;
                $pricePrint->product_id = $localProduct->id;
                $pricePrint->save();
            }

            //save ebook price
            if (isset($apiProductPrices->ebook)) {
                $pricePrint = Price::where([
                    'print' => 'ebook',
                    'product_id'  => $localProduct->id
                ])->first();

                if (!$pricePrint) {
                    $pricePrint = new Price();
                }
                $pricePrint->print = 'ebook';
                $pricePrint->USD = $apiProductPrices->ebook->USD;
                $pricePrint->GBP = $apiProductPrices->ebook->GBP;
                $pricePrint->EUR = $apiProductPrices->ebook->EUR;
                $pricePrint->AUD = $apiProductPrices->ebook->AUD;
                $pricePrint->INR = $apiProductPrices->ebook->INR;
                $pricePrint->product_id = $localProduct->id;
                $pricePrint->save();
            }

            // Save Category
            $category = Category::where('title', '=', $apiProduct->category)->first();
            if (!$category) {
                $category = new Category();
            }
            $category->title = $apiProduct->category;
            $category->save();
            $category->products()->sync($localProduct, false);

            // Save Authors
            foreach ($apiProduct->authors as $author) {
                $localAuthor = Author::where([
                    'packtId' => $author->id,
                    'name' => $author->name,
                    'about' => $author->about,
                    'profile_url' => $author->profile_url,

                ])->first();
                if (!$localAuthor) {
                    $localAuthor = new Author();
                }

                $localAuthor->packtId = $author->id;
                $localAuthor->name = $author->name;
                $localAuthor->about = $author->about;
                $localAuthor->profile_url = $author->profile_url;
                $localAuthor->save();
                $localProduct->authors()->sync($localAuthor, false);
            }


            // Save Concept
            $concept = Concept::where('title', '=', $apiProduct->concept)->first();
            if (!$concept) {
                $concept = new Concept();
            }
            $concept->title = $apiProduct->concept;
            $concept->save();
            $localProduct->concepts()->sync($concept, false);

            // Save Languages
            foreach ($apiProduct->languages as $language) {
                $localLanguage = Language::where([
                    'name' => $language->name,
                    'version' => $language->version,
                    'primary' => $language->primary,
                    'expertise' => $language->expertise,

                ])->first();
                if (!$localLanguage) {
                    $localLanguage = new Language();
                }

                $localLanguage->name = $language->name;
                $localLanguage->version = $language->version;
                $localLanguage->primary = $language->primary;
                $localLanguage->expertise = $language->expertise;
                $localLanguage->save();
                $localProduct->languages()->sync($localLanguage, false);
            }


            // Save JobRoles
            if ($apiProduct->jobroles) {
                $jobrole = Jobrole::where('name', '=', $apiProduct->jobroles)->first();
                if (!$jobrole) {
                    $jobrole = new Jobrole();
                }
                $jobrole->name = $apiProduct->jobroles;
                $jobrole->expertise = $apiProduct->expertise;
                $jobrole->save();
                $localProduct->jobroles()->sync($jobrole, false);
            }


            //Save tools
            if ($apiProduct->tools) {
                $localTool = Tool::where([
                    'name' => $apiProduct->tools->name,
                    'vendor' => $apiProduct->tools->vendor,
                    'type' => $apiProduct->tools->type,
                    'language' => $apiProduct->tools->language
                ])->first();
                if (!$localTool) {
                    $localTool = new Tool();
                }

                $localTool->name = $apiProduct->tools->name;
                $localTool->vendor = $apiProduct->tools->vendor;
                $localTool->type = $apiProduct->tools->type;
                $localTool->language = $apiProduct->tools->language;
                $localTool->save();
                $localProduct->tools()->sync($localTool, false);
            }


            // Save Vendors
            if ($apiProduct->vendors) {
                $vendor = Vendor::where('name', '=', $apiProduct->vendors->name)->first();
                if (!$vendor) {
                    $vendor = new Vendor();
                }
                $vendor->name = $apiProduct->vendors->name;
                $vendor->save();
                $localProduct->vendors()->sync($vendor, false);
            }
        }

        return Command::SUCCESS;
    }
}
