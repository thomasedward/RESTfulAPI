<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identifier' => (int)$seller->id,
            'name_seller' => (string)$seller->name,
            'email' => (string)$seller->email,
            'isVerified' => (int)$seller->verified,
            'creationDate' => (string)$seller->created_at,
            'lastChange' => (string)$seller->updated_at,
            'deletedDate' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
            'links' => [
                [
                    'rel'  => 'self',
                    'herf' => route('sellers.show',$seller->id),
                ],
                [
                    'rel'  => 'sellers.buyers',
                    'herf' => route('sellers.buyers.index',$seller->id),
                ],
                [
                    'rel'  => 'sellers.categories',
                    'herf' => route('sellers.categories.index',$seller->id),
                ],
                [
                    'rel'  => 'sellers.products',
                    'herf' => route('sellers.products.index',$seller->id),
                ],
                [
                    'rel'  => 'sellers.transactions',
                    'herf' => route('sellers.transactions.index',$seller->id),
                ],
                [
                    'rel'  => 'profile',
                    'herf' => route('users.show',$seller->id),
                ],

            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'name_seller' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'isAdmin' => 'admin',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }


    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'  => 'identifier' ,
            'name' => 'name_seller'  ,
            'email' =>'email'  ,
            'verified' =>'isVerified' ,
            'admin' => 'isAdmin'  ,
            'created_at' =>'creationDate'  ,
            'updated_at' =>  'lastChange'  ,
            'deleted_at' => 'deletedDate'  ,
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
