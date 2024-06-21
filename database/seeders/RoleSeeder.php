<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        $superAdmin = Role::create(['name' => 'super admin']);
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        $dosen = Role::create(['name' => 'dosen']);
        $tendik = Role::create(['name' => 'tendik']); // tendik: tenaga pendidik contohnya asisten lab dan staff
        // $staff = Role::create(['name' => 'staff']);
        $mahasiswa = Role::create(['name' => 'mahasiswa']);

        $superAdmin->syncPermissions(['admin-access']);

        $adminPermissions = ['admin-access', 'kpi-create', 'kpi-read', 'kpi-update', 'kpi-delete', 'presence-scopes-create', 'presence-scopes-read', 'presence-scopes-update', 'presence-scopes-delete', 'feedback-questions-create', 'feedback-questions-read', 'feedback-questions-update', 'feedback-questions-delete', 'employees-presence-quota-create', 'employees-presence-quota-read', 'employees-presence-quota-update', 'employees-presence-quota-delete', 'semesters-create', 'semesters-read', 'semesters-update', 'semesters-delete', 'majors-create', 'majors-read', 'majors-update', 'majors-delete', 'courses-create', 'courses-read', 'courses-update', 'courses-delete', 'feedbacks-create', 'feedbacks-read', 'feedbacks-update', 'feedbacks-delete', 'employees-create', 'employees-read', 'employees-update', 'employees-delete', 'students-create', 'students-read', 'students-update', 'students-delete', 'achievements-create', 'achievements-read', 'achievements-update', 'achievements-delete', 'tendik-positions-create', 'tendik-positions-read', 'tendik-positions-update', 'tendik-positions-delete'];
        $admin->syncPermissions($adminPermissions);
    }
}
