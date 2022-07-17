<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


      /*  $roles =
        [
            [
                'title' => 'Owner',
                'display' => 'المالك'

            ],
            [
                'title' => 'Employee',
                'display' => 'موظف'
            ]
        ];

        Role::insert($roles);

        $ownerRole = Role::all()->where('title' , '=' , 'Owner')->first();
        $employeeRole = Role::all()->where('title' , '=' , 'Employee')->first();*/


        // \App\Models\User::factory(10)->create();
    }
}
