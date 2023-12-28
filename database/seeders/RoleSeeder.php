<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $full_permissions = Permission::get()->pluck('name');

        $data = [
            [
                'name' => 'Super Admin',
                'permissions' => $full_permissions
            ],
            [
                'name' => 'Admin',
                'permissions' => ['view_general_dashboard', 'view_resident']
            ]
        ];

        // Create role and assign permission to role
        foreach ($data as $key => $value) {
            try {
                $role = Role::updateOrCreate([
                    'id' => $key + 1
                ], [
                    'id' => $key + 1,
                    'name' => $value["name"],
                ]);

                $role->syncPermissions($value['permissions']);
            } catch (\Exception $exception) {
                $this->command->info($exception->getMessage());
                // Do something when the exception
            }
        }
    }
}
