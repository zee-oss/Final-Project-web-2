<?php
namespace Database\Seeders;

use App\Models\{Role, Branch, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $ownerRole   = Role::where('name', 'owner')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $branch1     = Branch::where('code', 'CBG-001')->first();

        // Owner (Pak Jayusman)
        User::firstOrCreate(['email' => 'jayusman@simart.id'], [
            'name'      => 'Bapak Jayusman',
            'password'  => Hash::make('password'),
            'role_id'   => $ownerRole->id,
            'branch_id' => null,   // Owner akses semua cabang
            'is_active' => true,
        ]);

        // Manajer Toko Cabang 1
        User::firstOrCreate(['email' => 'manager1@simart.id'], [
            'name'      => 'Manager CBG-001',
            'password'  => Hash::make('password'),
            'role_id'   => $managerRole->id,
            'branch_id' => $branch1->id,
            'is_active' => true,
        ]);
    }
}