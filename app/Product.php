<?php

namespace App;
use App\Category;
use App\Seller;
use App\Transaction;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    public $transformer = ProductTransformer::class;

    protected $dates = ['deleted_at'];


    const AVAILABLE_PRODUCT= 'available';
    const UNAVAILABLE_PRODUCT= 'unavailable';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];


    protected $hidden = [
        'pivot  ',
    ];

    public function isAvailable()
    {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }
    public function isUnAvailable()
    {
        return $this->status == Product::UNAVAILABLE_PRODUCT;
    }
    //relations
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
