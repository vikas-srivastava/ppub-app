<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';


    /**
     * Get the images associated with the product.
     */
    public function image()
    {
        return $this->hasOne(ProductImage::class);
    }

    /**
     * Get the prices for the product.
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * The author that belong to the product.
     */
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'product_author');
    }

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    /**
     * The concepts that belong to the product.
     */
    public function concepts()
    {
        return $this->belongsToMany(Concept::class, 'product_concept');
    }

    /**
     * The languages that belong to the product.
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'product_language');
    }

    /**
     * The tools that belong to the product.
     */
    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'product_tool');
    }

    /**
     * The vendors that belong to the product.
     */
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'product_vendor');
    }

    /**
     * The jobroles that belong to the product.
     */
    public function jobroles()
    {
        return $this->belongsToMany(Jobrole::class, 'product_jobrole');
    }

    public function printPriceInGBP() 
    {
        $printPrice = $this->prices->where('print', '=', 'print')->first();

        if(!$printPrice) {
            $printPrice = $this->prices->where('print', '=', 'ebook')->first();
        }
        
        if($printPrice) {
            return '£'.$printPrice->GBP;
        } else {
            return 'n/a';
        }
    }
}
