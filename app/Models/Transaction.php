<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Product;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
       'account_id',
        'product_id',
        'operation',

    ];
    public function account(){
        return $this->belongsTo(\App\Models\Account::class);
    }
    public function product(){
        return $this->belongsTo(\App\Models\product::class);
    }


}
