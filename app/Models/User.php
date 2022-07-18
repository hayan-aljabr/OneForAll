<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Product;
use app\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use App\Models\OauthAccessToken;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        'user_type'
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
