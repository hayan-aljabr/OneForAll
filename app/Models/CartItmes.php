<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItmes extends Model
{
    use HasFactory;

    protected $fillable  = [

        'quantity',
        'product_id',
        'cart_id'

    ]   ;
    public function Product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
}
