<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Product;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public  function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('transformer.input:' . CategoryTransformer::class)->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , Product $product ,Category $category)
    {


         $product->categories()->syncWithoutDetaching([$category->id]);

        return  $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product ,Category $category)
    {
        if (!$product->categories()->find($category->id))
        {
            return $this->errorResponse('the specify category is not a category of this product',404);
        }
        $product->categories()->detach($category->id);
        return $this->showAll($product->categories);
    }



}
