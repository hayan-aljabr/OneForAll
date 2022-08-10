<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcmment extends Model
{
    use HasFactory;
    protected $fillable = [
        'announcmment'
    ];
    public function announcmment(){
        return $this->belongsTo(Announcmment::class);
    }

    }


