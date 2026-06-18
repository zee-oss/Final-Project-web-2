<?php
namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['code' => 'CBG-001', 'name' => 'SiMart Bandung Pusat',  'city' => 'Bandung'],
            ['code' => 'CBG-002', 'name' => 'SiMart Cimahi',         'city' => 'Cimahi'],
            ['code' => 'CBG-003', 'name' => 'SiMart Garut',          'city' => 'Garut'],
            ['code' => 'CBG-004', 'name' => 'SiMart Tasikmalaya',    'city' => 'Tasikmalaya'],
            ['code' => 'CBG-005', 'name' => 'SiMart Sukabumi',       'city' => 'Sukabumi'],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(['code' => $branch['code']], $branch);
        }
    }
}