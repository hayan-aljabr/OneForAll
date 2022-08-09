<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartItmes;

class Cart extends Model
{
    use HasFactory;
    protected $fillable  = [
        'user_id',
        'key'


    ]   ;
    public function cartitems(){
        return $this->hasMany(CartItmes::class);
    }
}
