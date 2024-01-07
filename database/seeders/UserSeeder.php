<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia123')
        ]);
        $admin->assignRole('Super Admin');

        $villages = Village::all()->pluck('id')->toArray();

        $admins = [];

        foreach ($villages as $village) {
            $admins[] = [
                'name' => 'Admin ' . Village::find($village)->name,
                'email' => Str::slug(Village::find($village)->name) . '@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('rahasia123'),
                'village_id' => $village,
            ];
        }

        User::insert($admins);

        $users = User::all();

        foreach ($users as $user) {
            if ($user->hasRole('Super Admin')) {
                continue;
            }
            $user->assignRole('Admin');
        }
    }
}
