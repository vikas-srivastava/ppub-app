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
        return $this->belongsToMany(Author::class);
    }

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * The concepts that belong to the product.
     */
    public function concepts()
    {
        return $this->belongsToMany(Concept::class);
    }

    /**
     * The languages that belong to the product.
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    /**
     * The tools that belong to the product.
     */
    public function tools()
    {
        return $this->belongsToMany(Tool::class);
    }

    /**
     * The vendors that belong to the product.
     */
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    /**
     * The jobroles that belong to the product.
     */
    public function jobroles()
    {
        return $this->belongsToMany(Jobrole::class);
    }
}
