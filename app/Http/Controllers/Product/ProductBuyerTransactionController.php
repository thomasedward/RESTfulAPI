<?php

namespace App\Http\Controllers\Product;

use App\Buyer;
use App\Product;
use App\Transaction;
use App\Transformers\TransactionTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    public  function __construct()
    {
        parent::__construct();
        $this->middleware('transformer.input:' . TransactionTransformer::class)->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,User $buyer ,Transaction $transaction)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];
        $this->validate($request,$rules);
        if ($buyer->id == $product->seller_id)
        {
            return $this->errorResponse('the Buyer must be different from the seller ',409);
        }

        if (!$buyer->isVerified())
        {
            return $this->errorResponse('the Buyer must be a verified user',409);
        }

        if (!$product->seller->isVerified())
        {
            return $this->errorResponse('the seller must be a verified user',409);
        }
        if (!$product->isAvailable())
        {
            return $this->errorResponse('the product must be a available user',409);
        }
        if ($product->quantity < $request->quantity)
        {
            return $this->errorResponse('the product does not have enough units for this transaction',409);
        }


        return DB::transaction(function () use ($request,$product,$buyer){

            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction);

        });


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
