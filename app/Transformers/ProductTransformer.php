<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => (int)$product->id,
            'product_title' => (string)$product->name,
            'product_description' => (string)$product->description,
            'product_stock' => (int)$product->quantity,
            'product_situation' => (string)$product->status,
            'product_picture' => url("img/{$product->image}"),
            'seller' => (int)$product->seller_id,
            'creationDate_product' => (string)$product->created_at,
            'lastChange_product' => (string)$product->updated_at,
            'deletedDate_product' => isset($product->deleted_at) ? (string) $product->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'product_title' => 'name',
            'product_description' => 'description',
            'product_stock' => 'quantity',
            'product_situation' => 'status',
            'product_picture' => 'image}',
            'seller' => 'seller_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
