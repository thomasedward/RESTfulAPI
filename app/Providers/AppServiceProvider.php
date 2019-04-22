<?php

namespace App\Providers;

use App\Product;
use function foo\func;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Product::updated(function($product){

            if ($product->quantity == 0 and $product->isAvailable())
            {
                $product->status = Product::UNAVAILABLE_PRODUCT;
                $product->save();
            }

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
