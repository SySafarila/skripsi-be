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

        $tendik = Role::create(['name' => 'tendik']); // tendik: tenaga pendidik contohnya asisten lab
        $staff = Role::create(['name' => 'staff']);
        $mahasiswa = Role::create(['name' => 'mahasiswa']);

        $superAdmin->syncPermissions(['admin-access']);
        $admin->syncPermissions(['admin-access']);
    }
}
