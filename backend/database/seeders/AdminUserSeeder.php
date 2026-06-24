<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@morisemi.jp'],
            [
                'name'     => '管理者',
                'password' => bcrypt('password'),
                'role'     => 'super_admin',
            ]
        );
    }
}
