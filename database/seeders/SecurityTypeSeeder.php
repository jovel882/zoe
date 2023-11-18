<?php

namespace Database\Seeders;

use App\Models\Security;
use App\Models\SecurityType;
use App\Models\SecurityPrice;
use Illuminate\Database\Seeder;

class SecurityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SecurityType::factory()->count(20)->create();
        
        SecurityPrice::factory()->create([
            'security_id' => Security::factory()->create([
                'security_type_id' => SecurityType::factory()->create([
                    'name' => 'mutual_funds'
                ])->id,
                'symbol' => 'TSLA',
            ])->id,
        ]);
    }
}
