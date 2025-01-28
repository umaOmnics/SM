<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ROLES
        DB::table('roles')->insert([
            'name' => 'Super Administrator',
            'slug' => 'super-administrator',
        ]);
        DB::table('roles')->insert([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // PERMISSIONS
        DB::table('permissions')->insert([
            'name' => 'Read',
            'slug' => 'read'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Create',
            'slug' => 'create'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Write',
            'slug' => 'write'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete',
            'slug' => 'delete'
        ]);


        if (!App::isProduction()) {

            // RESOURCES ROLES
            DB::table('roles_resources')->insert([
                ['resources_id' => 1, 'roles_id' => 1, 'permissions_id' => 1], ['resources_id' => 1, 'roles_id' => 1, 'permissions_id' => 2], ['resources_id' => 1, 'roles_id' => 1, 'permissions_id' => 3], ['resources_id' => 1, 'roles_id' => 1, 'permissions_id' => 4],
                ['resources_id' => 2, 'roles_id' => 1, 'permissions_id' => 1], ['resources_id' => 2, 'roles_id' => 1, 'permissions_id' => 2], ['resources_id' => 2, 'roles_id' => 1, 'permissions_id' => 3], ['resources_id' => 2, 'roles_id' => 1, 'permissions_id' => 4],
                ['resources_id' => 3, 'roles_id' => 1, 'permissions_id' => 1], ['resources_id' => 3, 'roles_id' => 1, 'permissions_id' => 2], ['resources_id' => 3, 'roles_id' => 1, 'permissions_id' => 3], ['resources_id' => 3, 'roles_id' => 1, 'permissions_id' => 4],
            ]);
            DB::table('roles_resources')->insert([
                ['resources_id' => 1, 'roles_id' => 2, 'permissions_id' => 1], ['resources_id' => 1, 'roles_id' => 2, 'permissions_id' => 2], ['resources_id' => 1, 'roles_id' => 2, 'permissions_id' => 3], ['resources_id' => 1, 'roles_id' => 2, 'permissions_id' => 4],
                ['resources_id' => 2, 'roles_id' => 2, 'permissions_id' => 1], ['resources_id' => 2, 'roles_id' => 2, 'permissions_id' => 2], ['resources_id' => 2, 'roles_id' => 2, 'permissions_id' => 3], ['resources_id' => 2, 'roles_id' => 2, 'permissions_id' => 4],
                ['resources_id' => 3, 'roles_id' => 2, 'permissions_id' => 1], ['resources_id' => 3, 'roles_id' => 2, 'permissions_id' => 2], ['resources_id' => 3, 'roles_id' => 2, 'permissions_id' => 3], ['resources_id' => 3, 'roles_id' => 2, 'permissions_id' => 4],
            ]);
        }

        if (env('APP_ENV') == 'local') {

            // USERS ROLES
            DB::table('users_roles')->insert([
                ['users_id' => 1, 'roles_id' => 1,],
            ]);
        } else {

            DB::table('users_roles')->insert([
                ['users_id' => 1, 'roles_id' => 1,],
            ]);

        }
    }
}
