<?php

namespace Database\Seeders;

use App\Models\User;
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
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super.admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);
        $superAdmin->syncRoles(['super admin']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);
        $admin->syncRoles(['admin']);

        $dosen = User::create([
            'name' => 'Dosen',
            'email' => 'dosen@dosen.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);
        $dosen->syncRoles(['dosen']);

        $tendik = User::create([
            'name' => 'Tendik',
            'email' => 'tendik@tendik.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);
        $tendik->syncRoles(['tendik']);

        $staff = User::create([
            'name' => 'staff',
            'email' => 'staff@staff.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);
        $staff->syncRoles(['staff']);

        $mahasiswa = User::create([
            'name' => 'mahasiswa',
            'email' => 'mahasiswa@mahasiswa.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now()
        ]);
        $mahasiswa->syncRoles(['mahasiswa']);
    }
}
