<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RolePermission::updateOrCreate([
            "id" => 1,
            "role_id" => 1,
            "permission_id" => 1,
        ]);
        RolePermission::updateOrCreate([
            "id" => 2,
            "role_id" => 1,
            "permission_id" => 2,
        ]);
        RolePermission::updateOrCreate([
            "id" => 3,
            "role_id" => 1,
            "permission_id" => 3,
        ]);
        RolePermission::updateOrCreate([
            "id" => 4,
            "role_id" => 1,
            "permission_id" => 4,
        ]);
        RolePermission::updateOrCreate([
            "id" => 5,
            "role_id" => 1,
            "permission_id" => 5,
        ]);
        RolePermission::updateOrCreate([
            "id" => 6,
            "role_id" => 1,
            "permission_id" => 6,
        ]);
        RolePermission::updateOrCreate([
            "id" => 7,
            "role_id" => 1,
            "permission_id" => 7,
        ]);

        RolePermission::updateOrCreate([
            "id" => 8,
            "role_id" => 1,
            "permission_id" => 8,
        ]);

        RolePermission::updateOrCreate([
            "id" => 9,
            "role_id" => 1,
            "permission_id" => 9,
        ]);

        RolePermission::updateOrCreate([
            "id" => 10,
            "role_id" => 1,
            "permission_id" => 10,
        ]);

        RolePermission::updateOrCreate([
            "id" => 11,
            "role_id" => 1,
            "permission_id" => 11,
        ]);

        RolePermission::updateOrCreate([
            "id" => 12,
            "role_id" => 1,
            "permission_id" => 12,
        ]);

        RolePermission::updateOrCreate([
            "id" => 13,
            "role_id" => 1,
            "permission_id" => 13,
        ]);

        RolePermission::updateOrCreate([
            "id" => 14,
            "role_id" => 1,
            "permission_id" => 14,
        ]);

        RolePermission::updateOrCreate([
            "id" => 15,
            "role_id" => 1,
            "permission_id" => 15,
        ]);

        RolePermission::updateOrCreate([
            "id" => 16,
            "role_id" => 1,
            "permission_id" => 16,
        ]);

        RolePermission::updateOrCreate([
            "id" => 17,
            "role_id" => 1,
            "permission_id" => 17,
        ]);

        RolePermission::updateOrCreate([
            "id" => 18,
            "role_id" => 1,
            "permission_id" => 18,
        ]);

        RolePermission::updateOrCreate([
            "id" => 19,
            "role_id" => 1,
            "permission_id" => 19,
        ]);

        RolePermission::updateOrCreate([
            "id" => 20,
            "role_id" => 1,
            "permission_id" => 20,
        ]);
        RolePermission::updateOrCreate([
            "id" => 21,
            "role_id" => 1,
            "permission_id" => 21,
        ]);
        RolePermission::updateOrCreate([
            "id" => 22,
            "role_id" => 1,
            "permission_id" => 22,
        ]);


    }
}
