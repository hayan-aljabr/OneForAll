<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Wishlist extends Model
{
    use HasFactory;
    protected $tabel = "Wishlists";
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function product(){
        return $this->belongsTos(Product::class);
    }
}
