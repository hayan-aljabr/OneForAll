<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wishlist;
use App\Models\Report;
use App\Models\Featured;

class Product extends Model
{
    use HasFactory;
    protected $fillable  = [
        'name',
        'price',
        'description',
        'image_url',
        'quantity',
        'category_id',
        'user_id'

    ]   ;

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reviews(){
        return $this->hasMany(\App\Models\Review::class);
    }

    public function wishlist(){
        return $this->belongsTo(Wishlist::class);
     }
     public function reports(){
        return $this->hasMany(Report::class);
     }
     public function featured(){
        return $this->belongsTo(\App\Models\Featured::class, 'id', 'id');
    }
    public function avgReview(){
        return $this->reviews()->avg('rating');

    }


    protected $hidden = [];

}
