<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'concepts';

    /**
     * The products that belong to the concept.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_concept');
    }
}
