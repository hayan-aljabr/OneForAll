<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItmes extends Model
{
    use HasFactory;

    protected $fillable  = [

        'quantity',
        'product_id',
        'cart_id'

    ]   ;
    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function cart(){
        return $this->belongsTo(Cart::class);
    }
}
