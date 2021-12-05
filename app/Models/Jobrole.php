<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobrole extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobroles';

    /**
     * The products that belong to the jobrole.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
