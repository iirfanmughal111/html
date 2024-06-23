<?php

use App\Models\PermissionCategory;
use Illuminate\Database\Seeder;

class PermissionCategoryTableSeeder extends Seeder
{
    public function run()
    {
        $PermissionCategory = [[
            'id'         => 1,
            'name'      => 'Dashboard',
            'slug'      => 'dashbaord',
        ], 
		[
			'id'         => 2,
            'name'      => 'Customers',
            'slug'      => 'customers',
		], 
		[
			'id'         => 3,
            'name'      => 'Email',
            'slug'      => 'email',
		], 
		[
			'id'         => 4,
            'name'      => 'Config',
            'slug'      => 'config',
		], 
		[
			'id'         => 5,
            'name'      => 'Roles',
            'slug'      => 'roles',
		], 
		[
			'id'         => 6,
            'name'      => 'Account',
            'slug'      => 'account',
		], 
		[
			'id'         => 7,
            'name'      => 'CMS Pages',
            'slug'      => 'cmspages',
		],
        [
            'id'         => 8,
            'name'      => 'Games',
            'slug'      => 'games',
        ],
        [
            'id'         => 9,
            'name'      => 'Game Guides',
            'slug'      => 'game-guides',
        ],
        [
            'id'         => 10,
            'name'      => 'Tournaments',
            'slug'      => 'tournaments',
        ]
		];

        foreach ($PermissionCategory as $PermissionCat) {
            PermissionCategory::updateOrCreate(['id' => $PermissionCat['id']], $PermissionCat);
        }

        //PermissionCategory::insert($PermissionCategory);
    }
}
