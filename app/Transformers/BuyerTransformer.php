<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' => (int)$buyer->id,
            'name_buyer' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'isVerified' => (int)$buyer->verified,
            'creationDate' => (string)$buyer->created_at,
            'lastChange' => (string)$buyer->updated_at,
            'deletedDate' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
            'links' => [
                [
                    'rel'  => 'self',
                    'herf' => route('buyers.show',$buyer->id),
                ],
                [
                    'rel'  => 'buyers.sellers',
                    'herf' => route('buyers.sellers.index',$buyer->id),
                ],
                [
                    'rel'  => 'buyers.categories',
                    'herf' => route('buyers.categories.index',$buyer->id),
                ],
                [
                    'rel'  => 'buyers.products',
                    'herf' => route('buyers.products.index',$buyer->id),
                ],
                [
                    'rel'  => 'buyers.transactions',
                    'herf' => route('buyers.transactions.index',$buyer->id),
                ],
                [
                    'rel'  => 'profile',
                    'herf' => route('users.show',$buyer->id),
                ],

            ]


        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'name_buyer' => 'name',
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
            'name' => 'name_buyer'  ,
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
