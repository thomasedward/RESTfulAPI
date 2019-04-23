<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int)$user->id,
            'name_user' => (string)$user->name,
            'email' => (string)$user->email,
            'isVerified' => (int)$user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'creationDate' => (string)$user->created_at,
            'lastChange' => (string)$user->updated_at,
            'deletedDate' => isset($user->deleted_at) ? (string) $user->deleted_at : null,
            'links' => [
                [
                    'rel'  => 'self',
                    'herf' => route('users.show',$user->id),
                ],

            ]

        ];
    }
    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'name_user' => 'name',
            'email' => 'email',
            'password' => 'password',
            'password_confirmation' => 'password_confirmation',
            'verification_token' => 'verification_token',
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
            'name' => 'name_user'  ,
            'email' =>'email'  ,
            'password' => 'password',
            'password_confirmation' => 'password_confirmation',
            'verification_token' => 'verification_token',
            'verified' =>'isVerified' ,
            'admin' => 'isAdmin'  ,
            'created_at' =>'creationDate'  ,
            'updated_at' =>  'lastChange'  ,
            'deleted_at' => 'deletedDate'  ,
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
