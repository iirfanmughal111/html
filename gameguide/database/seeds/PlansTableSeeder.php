<?php

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [[
            'id'         => 1,
            'name'      => 'Beginner',
            'description' => 'For people just starting out and want to level up their game play.',
            'amount'	 => '0.00',
            'created_at' => '2020-10-10 19:13:32',
            'updated_at' => '2020-10-10 19:13:32',
            'deleted_at' => null,
        ], 
		[
            'id'         => 2,
            'name'      => 'Ultimate',
            'description' => 'For people just starting out and want to level up their game play.',
            'amount'	 => '20.00',
            'created_at' => '2020-10-10 19:13:32',
            'updated_at' => '2020-10-10 19:13:32',
            'deleted_at' => null,
        ]
		];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['id' => $plan['id']], $plan);
        }
    }
}
