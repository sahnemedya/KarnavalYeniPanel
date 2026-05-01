<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            RoleUserSeeder::class,
            LanguageSeeder::class,
            SiteSettingsSeeder::class,
            ApiKeySeeder::class,
            CategorySeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            ContactsSeeder::class,
            SocialMediaSeeder::class,
            BladeSeeder::class
        ]);
    }
}
