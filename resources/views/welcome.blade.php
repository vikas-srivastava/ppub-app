@extends('layouts.app')

@section('content')

<section class="bg-white py-8">

    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

        <nav id="store" class="w-full z-30 top-0 px-6 py-1">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl " href="#">
                    Latest Products
                </a>
            </div>
        </nav>

        @foreach($latestProducts as $latestProduct)
        <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col">
            <a href="#" title="{{ $latestProduct->tagline }}">
                @if($latestProduct->image)
                <img class="hover:grow hover:shadow-lg" src="{{ asset('storage/' . $latestProduct->image->largeImage()) }}" max-width="400" max-height="400">
                @else
                <img class="hover:grow hover:shadow-lg" src="https://via.placeholder.com/400">
                @endif
                <div class="pt-3 flex items-center justify-between">
                    <p class="" style="min-height: 84px;">{{ $latestProduct->title }}</p>

                </div>
            </a>
            @if($latestProduct->prices)
            <p class="pt-1 pb-2 text-gray-900"><b>{{ $latestProduct->printPriceInGBP() }}</b></p>
            @endif

            <a href="https://packt.link/a/{{ $latestProduct->isbn10 }}"> <button class="w-full text-white font-bold py-2 px-4 rounded" style="background-color: #ec6611;">Buy
                </button></a>

        </div>
        @endforeach

    </div>


    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

        <nav id="store" class="w-full z-30 top-0 px-6 py-1">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl " href="#">
                    Random Products
                </a>

            </div>
        </nav>

        @foreach($randomProducts as $randomProduct)
        <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col">
            <a href="#">
                @if($randomProduct->image)
                <img class="hover:grow hover:shadow-lg" src="{{ asset('storage/' . $randomProduct->image->largeImage()) }}" max-width="400" max-height="400">
                @else
                <img class="hover:grow hover:shadow-lg" src="https://via.placeholder.com/400">
                @endif
            </a>
            <div class="pt-3 flex items-center justify-between">
                <p class="" style="min-height: 84px;">{{ $randomProduct->title }}</p>

            </div>

            @if($randomProduct->prices)
            <p class="pt-1 pb-2 text-gray-900"><b>{{ $randomProduct->printPriceInGBP() }}</b></p>
            @endif

            <a href="https://packt.link/a/{{ $randomProduct->isbn10 }}"> <button class="w-full text-white font-bold py-2 px-4 rounded" style="background-color: #ec6611;">Buy
                </button></a>

        </div>
        @endforeach

    </div>

    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

        <nav id="store" class="w-full z-30 top-0 px-6 py-1">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl " href="#">
                    Categories
                </a>

            </div>
        </nav>

        @foreach($categories as $category)
        <nav id="store" class="w-full z-30 top-0 px-6 py-1">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl " href="#">
                    {{ $category->title }}
                </a>

            </div>
        </nav>

        @foreach($category->products->take(4) as $product)
        <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col">
            <a href="#">
                @if($product->image)
                <img class="hover:grow hover:shadow-lg" src="{{ asset('storage/' . $product->image->largeImage()) }}" max-width="400" max-height="400">
                @else
                <img class="hover:grow hover:shadow-lg" src="https://via.placeholder.com/400">
                @endif
            </a>
            <div class="pt-3 flex items-center justify-between">
                <p class="" style="min-height: 84px;">{{ $product->title }}</p>

            </div>

            @if($product->prices)
            <p class="pt-1 pb-2 text-gray-900"><b>{{ $product->printPriceInGBP() }}</b></p>
            @endif
            <a href="https://packt.link/a/{{ $product->isbn10 }}"> <button class="w-full text-white font-bold py-2 px-4 rounded" style="background-color: #ec6611;">Buy
                </button></a>

        </div>
        @endforeach

        @endforeach

    </div>

</section>

<section class="bg-white py-8">

    <div class="container py-8 px-6 mx-auto">

        <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl mb-8" href="#">
            About
        </a>

        <p class="mt-8 mb-8">This template is inspired by the stunning nordic minamalist design - in particular:
            <br>
            <a class="text-gray-800 underline hover:text-gray-900" href="http://savoy.nordicmade.com/" target="_blank">Savoy Theme</a> created by <a class="text-gray-800 underline hover:text-gray-900" href="https://nordicmade.com/">https://nordicmade.com/</a> and <a class="text-gray-800 underline hover:text-gray-900" href="https://www.metricdesign.no/" target="_blank">https://www.metricdesign.no/</a>
        </p>


    </div>

</section>



@endsection