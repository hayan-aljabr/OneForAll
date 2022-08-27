<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use App\Models\School;
use APP\Models\Classe;
use HasApiTokens;

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
