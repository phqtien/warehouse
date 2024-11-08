<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Nếu đã tạo Model Role
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminUser = User::where('email', 'admin@gmail.com')->first();
        $normalUser = User::where('email', 'user@gmail.com')->first();

        if ($adminUser) {
            Role::create([
                'user_id' => $adminUser->id,
                'name' => 'admin',
            ]);
        }

        if ($normalUser) {
            Role::create([
                'user_id' => $normalUser->id,
                'name' => 'user',
            ]);
        }
    }
}
