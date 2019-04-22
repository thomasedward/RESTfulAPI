<?php

namespace App\Http\Controllers\Seller;

use App\Product;
use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Seller $seller)
    {
        $rules = [
            'name' => 'required|regex:/^[A-Za-z\s-_]+$/|string',
            'description' => 'required',
            'quantity' => 'required',
            'image' => 'required|image',
            ];
        $this->validate($request,$rules);
        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = '1.jpg';
        $data['seller_id'] = $seller->id;
        $product  = Product::create($data);
        return $this->showOne($product);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller , Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:'.Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT,
            'image' => 'image',
        ];
        $this->validate($request,$rules);
        $this->checkSeller($seller,$product);

        $product->fill($request->intersect([
            'name','description','quantity'
        ]));
        if ($request->has('status'))
        {
            $product->status = $request->status;
            if ($product->isAvailable() and $product->categories()->count() == 0 )
            {
                return $this->errorResponse('An active product must have at least one category',409);
            }
        }
        if ($product->isClean())
        {
            return $this->errorResponse('you neet to specify a different  value to update ',422);
        }

        $product->save();
        return  $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller,Product $product)
    {
        $this->checkSeller($seller,$product);
        $product->delete();
        return $this->showOne($product);
    }

    protected  function checkSeller(Seller $seller , Product $product)
    {
            if ($seller->id != $product->seller_id)
            {
                throw new HttpException(422,'the specified seller is not the actual seller of the product');
            }
    }
}
