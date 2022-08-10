<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        //'user_name',
        'status',
        'balance',
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
