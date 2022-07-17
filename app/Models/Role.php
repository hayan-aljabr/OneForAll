<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;


    public function permissions(){

        return $this->belongsToMany(Premissions::class)
            ->select('permission_id','title');
    }


    public function check($param){

        $premission = Permission::query()->where('title', '=' , $param)->first();

        return RolePermission::query()
        ->where('permission_id','=' , $premission->id)
        ->where('role_id' , '=' , $this->id)
        ->exists();
    }
}
