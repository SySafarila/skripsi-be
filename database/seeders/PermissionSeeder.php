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

        // presence scopes
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'presence-scopes-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'presence-scopes-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'presence-scopes-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'presence-scopes-delete',
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

        // feedbacks
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'feedbacks-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'feedbacks-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'feedbacks-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'feedbacks-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // employees-presence-quota / pengelolaan dosen
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'employees-presence-quota-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'employees-presence-quota-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'employees-presence-quota-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'employees-presence-quota-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // semesters
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'semesters-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'semesters-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'semesters-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'semesters-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // majors
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'majors-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'majors-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'majors-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'majors-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // courses
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'courses-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'courses-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'courses-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'courses-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // employees
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'employees-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'employees-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'employees-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'employees-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // students
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'students-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'students-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'students-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'students-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // achievements
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'achievements-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'achievements-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'achievements-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'achievements-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        // tendik-positions
        array_push(
            $arr,
            [
                'guard_name' => 'web',
                'name' => 'tendik-positions-create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'tendik-positions-read',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'tendik-positions-update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'guard_name' => 'web',
                'name' => 'tendik-positions-delete',
                'created_at' => now(),
                'updated_at' => now()
            ],
        );

        DB::table('permissions')->insert($arr);
    }
}
