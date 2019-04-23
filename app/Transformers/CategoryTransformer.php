<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'category_title' => (string)$category->name,
            'category_details' => (string)$category->description,
            'creationDate_category' => (string)$category->created_at,
            'lastChange_category' => (string)$category->updated_at,
            'deletedDate_category' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
            'links' => [
                [
                    'rel'  => 'self',
                    'herf' => route('categories.show',$category->id),
                ],
                [
                    'rel'  => 'categories.buyers',
                    'herf' => route('categories.buyers.index',$category->id),
                ],
                [
                    'rel'  => 'categories.products',
                    'herf' => route('categories.products.index',$category->id),
                ],
                [
                    'rel'  => 'categories.sellers',
                    'herf' => route('categories.sellers.index',$category->id),
                ],
                [
                    'rel'  => 'categories.transactions',
                    'herf' => route('categories.transactions.index',$category->id),
                ],

            ]

        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'category_title' => 'name',
            'category_details' => 'description',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'  =>      'identifier' ,
            'name'    =>  'category_title'  ,
            'description'  =>'category_details' ,
            'created_at' =>'creationDate'  ,
            'updated_at' =>  'lastChange'  ,
            'deleted_at' => 'deletedDate'  ,
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
