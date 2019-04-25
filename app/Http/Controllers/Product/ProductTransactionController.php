<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;

class ProductTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $transactions = $product->whereHas('transactions')->with('transactions')->get()
            ->pluck('transactions')
            ->collapse();
        //->pluck('buyer')
        //->unique('id')
        //->values();
        return $this->showAll($transactions);
    }


}
