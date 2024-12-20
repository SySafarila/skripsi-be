<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CrudPermissionTest extends TestCase
{
    use RefreshDatabase;
    protected $seed = true;

    public function test_hak_akses_create()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response1 = $this->actingAs($super_admin)->get(route('admin.permissions.create'));
        $response1->assertStatus(200);

        $response = $this->actingAs($super_admin)->post(route('admin.permissions.store'), [
            'name' => 'testing'
        ]);

        $response->assertRedirect(route('admin.permissions.index'));
    }

    public function test_hak_akses_read()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->get(route('admin.permissions.index'));

        $response->assertStatus(200);
    }

    public function test_hak_akses_update()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.permissions.store'), [
            'name' => 'testing'
        ]);
        $response->assertRedirect(route('admin.permissions.index'));

        $permission = Permission::findByName('testing');
        $response1 = $this->actingAs($super_admin)->get(route('admin.permissions.edit', $permission->id));
        $response1->assertStatus(200);
        $response2 = $this->actingAs($super_admin)->patch(route('admin.permissions.update', $permission->id), [
            'name' => 'testing2'
        ]);
        $response2->assertRedirect(route('admin.permissions.index'));
    }

    public function test_hak_akses_delete()
    {
        // $this->seed();
        $super_admin = User::where('email', 'super.admin@admin.com')->first();

        $response = $this->actingAs($super_admin)->post(route('admin.permissions.store'), [
            'name' => 'testing'
        ]);
        $response->assertRedirect(route('admin.permissions.index'));

        $permission = Permission::findByName('testing');
        $response2 = $this->actingAs($super_admin)->delete(route('admin.permissions.destroy', $permission->id));
        $response2->assertRedirect(route('admin.permissions.index'));
    }
}
