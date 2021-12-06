<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('packtId')->unique();
            $table->string('isbn13')->unique();
            $table->string('isbn10')->unique();

            $table->string('title');
            $table->string('product_type');
            $table->string('tagline', 500);
            $table->string('pages');
            $table->dateTime('publication_date');
            $table->string('length');
            
            $table->mediumText('learn');
            $table->mediumText('features');
            $table->mediumText('description');
            $table->string('url');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
