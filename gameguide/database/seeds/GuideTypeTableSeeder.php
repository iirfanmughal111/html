<?php

use Illuminate\Database\Seeder;
use App\Models\GuideType;

class GuideTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guideTypes = [[
            'id'         => 1,
            'name'      => 'text',
            'created_at' => '2020-10-10 19:13:32',
            'updated_at' => '2020-10-10 19:13:32',
            'deleted_at' => null,
        ], 
		[
            'id'         => 2,
            'name'      => 'video',
            'created_at' => '2020-10-10 19:13:32',
            'updated_at' => '2020-10-10 19:13:32',
            'deleted_at' => null,
        ]
		];

        foreach ($guideTypes as $guideType) {
            GuideType::updateOrCreate(['id' => $guideType['id']], $guideType);
        }
    }
}
