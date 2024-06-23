<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [[
            'id'         => 1,
            'title'      => 'Super Admin',
            'slug'      => 'super-admin',
            'created_at' => '2020-10-05 19:13:32',
            'updated_at' => '2020-10-05 19:13:32',
            'deleted_at' => null,
        ], 
		[
			'id'         => 2,
            'title'      => 'User',
            'slug'      => 'user',
            'created_at' => '2020-10-05 19:13:32',
            'updated_at' => '2020-10-05 19:13:32',
            'deleted_at' => null,
		],
        [
            'id'         => 3,
            'title'      => 'Coach',
            'slug'      => 'coach',
            'created_at' => '2020-10-05 19:13:32',
            'updated_at' => '2020-10-05 19:13:32',
            'deleted_at' => null,
        ]
		];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }

        //Role::insert($roles);
    }
}
