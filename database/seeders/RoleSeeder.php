<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'owner',      'display_name' => 'Pemilik (Owner)'],
            ['name' => 'manager',    'display_name' => 'Manajer Toko'],
            ['name' => 'supervisor', 'display_name' => 'Supervisor'],
            ['name' => 'cashier',    'display_name' => 'Kasir'],
            ['name' => 'warehouse',  'display_name' => 'Pegawai Gudang'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}