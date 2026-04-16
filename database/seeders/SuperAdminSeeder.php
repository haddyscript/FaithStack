<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@faithstack.test'],
            [
                'name'      => 'Platform Admin',
                'email'     => 'superadmin@faithstack.test',
                'password'  => Hash::make('admin123'),
                'role'      => 'superadmin',
                'tenant_id' => null,
            ]
        );

        $this->command->info('Super admin created: superadmin@faithstack.test / superadmin123');
    }
}
