<?php

namespace App\Models;

use app\Models\Order;
use App\Models\Report;
use App\Models\Account;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\OauthAccessToken;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Loyalty;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        //'user_name',
        'email',
        'password',
        'phone_number',
        'age',
        'user_type',
        'baio',
        'profile_photo'
    ];

  /*  public function isAdmin()
    {
        if($this->user_type === 'ADM' OR 'SADM')
        {
            return true;
        }
        else
        {
            return false;
        }
    }*/

    public function products(){
        return $this->hasManyThrough(\App\Models\Product::class, User::class, 'user_id','id');
     }

     public function orders()
{
    return $this->hasMany(Order::class);
}

public function reviews(){
    return $this->hasMany(\App\Models\Review::class);
}
public function wishlist(){
    return $this->hasMany(Wishlist::class);
 }

public function reports(){
    return $this->hasMany(\App\Models\Report::class);
 }
 public function account(){
    return $this->hasOne(\App\Models\Account::class);
 }
 public function loyalty(){
    return $this->hasMany(\App\Models\Loyalty::class);
 }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
  ];

}
