<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable  = [
        'products',
        'totalPrice',
        'name',
        'address',
        'phone',
        'email',
        'transactionID',
        'user_id'

    ]   ;

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
