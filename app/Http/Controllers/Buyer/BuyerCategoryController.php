<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categores = $buyer->transactions()
                           ->with('product.categories')
                           ->get()
                           ->pluck('product.categories')
                           ->unique('id')
                           ->values();
        return $this->showAll($categores);
    }


}
