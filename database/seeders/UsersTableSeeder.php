<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'                => 1,
                'full_name'         => 'Admin',	
                'password'          => bcrypt('password'),
                'email'          => 'admin@admin.com',
            ],
        ];

        Admin::insert($users);
    }
}
