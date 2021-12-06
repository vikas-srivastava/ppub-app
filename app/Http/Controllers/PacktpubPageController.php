<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class PacktpubPageController extends Controller
{   
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $latestProducts = Product::all()->sortByDesc('publication_date')->take(12);
        $randomProducts = Product::all()->random(4);
        $categories = Category::all();

        return view('welcome', [
            'latestProducts' => $latestProducts, 
            'randomProducts' => $randomProducts,
            'categories' => $categories
        ]);
    }
}
