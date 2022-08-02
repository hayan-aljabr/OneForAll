<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable  = [
        'name',
        'thecategory_id',



    ]   ;
    public function childs()
    {
        return $this->hasMany('\App\Models\Category' , 'thecategory_id');
    }

    public function parent()
    {

        $this->belongsTo('\App\Models\Category');
    }


    public function products(){
       return $this->hasManyThrough(\App\Models\Product::class, Category::class, 'thecategory_id','category_id', 'id');
    }
    protected $hidden = ['created_at', 'updated_at'];
}
