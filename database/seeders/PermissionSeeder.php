<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];

        // system
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'admin-access',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // permissions
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'permissions-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'permissions-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'permissions-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'permissions-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // roles
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'roles-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'roles-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'roles-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'roles-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // users
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'users-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'users-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'users-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'users-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // kpi
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'kpi-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'kpi-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'kpi-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'kpi-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // subjects / mata kuliah
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'subjects-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'subjects-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'subjects-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'subjects-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // feedback-questions
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'feedback-questions-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'feedback-questions-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'feedback-questions-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'feedback-questions-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        DB::table('permissions')->insert($arr);
    }
}
