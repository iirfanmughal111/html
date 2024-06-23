<?php 
use App\Models\PermissionList;
use Illuminate\Database\Seeder;

class PermissionListTableSeeder extends Seeder
{
    public function run()
    {
		$PermissionList = [
		['id'         => 1, 'category_id'=>3, 'name'=>'Email: Listing', 'slug'=>'email_listing'],
		['id'         => 2, 'category_id'=>3, 'name'=>'Email: Edit', 'slug'=>'email_edit'],
		['id'         => 3, 'category_id'=>4, 'name'=>'Config: Listing','slug'=> 'config_listing'],
		['id'         => 4,'category_id'=> 2,'name'=> 'Customer: Listing', 'slug'=>'customer_listing'],
		['id'         => 5, 'category_id'=>2, 'name'=>'Customer: Edit', 'slug'=>'customer_edit'],
		['id'         => 6, 'category_id'=>2, 'name'=>'Customer: Manage','slug'=> 'customer_manage'],
		['id'         => 7, 'category_id'=>2, 'name'=>'Customer Status: Edit','slug'=> 'customer_status_edit'],
		['id'         => 8,'category_id'=> 1, 'name'=>'Dashboard : Listing', 'slug'=>'dashboard_listing'],
		['id'         => 9, 'category_id'=>5, 'name'=>'Roles: Listing','slug'=> 'roles_listing'],
		['id'         => 10, 'category_id'=>5, 'name'=>'Roles: Edit', 'slug'=>'roles_edit'],
		['id'         => 11, 'category_id'=>5, 'name'=>'Roles: Create New','slug'=> 'roles_create'],
		['id'         => 12, 'category_id'=>2, 'name'=>'Customer: Create New', 'slug'=>'customer_create'],
		['id'         => 13, 'category_id'=>6, 'name'=>'Account: Listing', 'slug'=>'account_listing'],
		['id'         => 14, 'category_id'=>6, 'name'=>'Account:Edit', 'slug'=>'account_edit'],
		['id'         => 15, 'category_id'=>6, 'name'=>'Account:Reset Password','slug'=> 'account_reset_password'],
		['id'         => 16, 'category_id'=>2, 'name'=>'Customer: Delete','slug'=> 'customer_delete'],
		['id'         => 17, 'category_id'=>7, 'name'=>'CMS Pages: Listing', 'slug'=>'cms_pages_listing'],
		['id'         => 18, 'category_id'=>7, 'name'=>'CMS Pages: Create', 'slug'=>'cms_pages_create'],
		['id'         => 19, 'category_id'=>7, 'name'=>'CMS Pages: Edit', 'slug'=>'cms_pages_edit'],
		['id'         => 20, 'category_id'=>7, 'name'=>'CMS Pages: Delete', 'slug'=>'cms_pages_delete'],
		
		['id'         => 21, 'category_id'=>8, 'name'=>'Game: Listing', 'slug'=>'game_listing'],
		['id'         => 22, 'category_id'=>8, 'name'=>'Game: Create', 'slug'=>'game_create'],
		['id'         => 23, 'category_id'=>8, 'name'=>'Game: Edit', 'slug'=>'game_edit'],
		['id'         => 24, 'category_id'=>8, 'name'=>'Game: Delete', 'slug'=>'game_delete'],
		['id'         => 25, 'category_id'=>9, 'name'=>'Game Guides: Listing', 'slug'=>'game_guides_listing'],
		['id'         => 26, 'category_id'=>9, 'name'=>'Game Guides: Create', 'slug'=>'game_guides_create'],
		['id'         => 27, 'category_id'=>9, 'name'=>'Game Guides: Edit', 'slug'=>'game_guides_edit'],
		['id'         => 28, 'category_id'=>9, 'name'=>'Game Guides: Delete', 'slug'=>'game_guides_delete'],
		['id'         => 29, 'category_id'=>10, 'name'=>'Tournaments: Listing', 'slug'=>'tournament_listing'],
		['id'         => 30, 'category_id'=>10, 'name'=>'Tournaments: Create', 'slug'=>'tournament_create'],
		['id'         => 31, 'category_id'=>10, 'name'=>'Tournaments: Edit', 'slug'=>'tournament_edit'],
		['id'         => 32, 'category_id'=>10, 'name'=>'Tournaments: Delete', 'slug'=>'tournament_delete']
		];

		foreach ($PermissionList as $PrmnList) {
            PermissionList::updateOrCreate(['id' => $PrmnList['id']], $PrmnList);
        }

        //PermissionList::insert($PermissionList);
    }
}
?>