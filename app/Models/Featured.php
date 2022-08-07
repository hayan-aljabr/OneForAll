<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Featured extends Model
{
    use HasFactory;
    protected $fillable  = [
        'product_id'

    ]   ;

    public function product(){
        return $this->hasMany(Product::class);
    }
}
