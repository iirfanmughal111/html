<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Mail\testmail;
use Illuminate\Support\Facades\Mail;

Route::group([ 'prefix' => 'admin','middleware' => 'admin','namespace' => 'Admin'], function () {

	Route::get('/','AdminController@index');
	
	Route::get('logout', 'AdminController@logout');
	
	Route::post('user/enable-disable',array('uses'=>'UsersController@enableDisableUser'));
	Route::post('user/delete_user/{id}', 'UsersController@delete_user')->name('users.delete');
	
	//Dashboard
	Route::get('dashboard',array('uses'=>'DashboardController@index'));
	
	Route::post('update-profile/{user_id}', 'UsersController@profileUpdate');//UPDATE USER
	Route::post('update-basic-profile/{user_id}', 'UsersController@updateBasicProfile');//UPDATE Basic USER
	Route::post('reset-password/{user_id}', 'UsersController@passwordUpdate');
	//roles
	Route::get('roles',array('uses'=>'RolesController@roles'));
	Route::post('roles/edit/{request_id}', 'RolesController@roles_edit'); //Edit request
	Route::get('role/create/',array('uses'=>'RolesController@role_create')); //Edit User
	Route::post('role/delete_role/{request_id}',array('uses'=>'RolesController@role_delete')); //Edit User
	Route::post('/create-role-permissions/',array('uses'=>'RolesController@role_permission_create')); //Edit User
	Route::post('/update-role-permissions/',array('uses'=>'RolesController@role_permission_update')); //Edit User
	
	Route::post('uploads/logo/{request_id}',array('uses'=>'SettingsController@uploadLogo'));
	Route::post('fetch/logo/{request_id}',array('uses'=>'SettingsController@getLogo'));
	Route::post('delete/logo/{request_id}',array('uses'=>'SettingsController@deleteLogo'));
	
	
	//cms pages
	Route::get('cms-pages',array('uses'=>'CmsController@index'));
	Route::get('cms-pages/edit/{request_id}', 'CmsController@cms_page_edit'); //Edit request
	Route::get('cms-pages/create/',array('uses'=>'CmsController@cms_page_create')); //Edit User
	Route::post('cms-pages/delete_page/{request_id}',array('uses'=>'CmsController@page_delete')); //Edit User
	Route::post('cms-page-new',array('uses'=>'CmsController@cms_page_new')); //Edit User
	Route::post('cms-page-update',array('uses'=>'CmsController@cms_page_update')); //Edit User
	
	// Global Setting 
	Route::get('settings',array('uses'=>'SettingsController@index'));
	Route::get('site-settings',array('uses'=>'SettingsController@site_settings'));
	Route::post('update/email/{request_id}',array('uses'=>'SettingsController@update_email_settings'));
	Route::post('update/site/{request_id}',array('uses'=>'SettingsController@update_site_settings'));
	route::get('settings/ac-settings',array('uses'=>'SettingsController@accesscodes_settings'))->name('save.data');
	
	
	//EMAIL TEMPLATE 
	Route::get('emails',array('uses'=>'EmailController@index'));
	Route::get('email/edit/{template_id}',array('uses'=>'EmailController@email_template_edit'));
	Route::post('email/update',array('uses'=>'EmailController@email_template_update'));
	
	//Games
	Route::get('games',array('uses'=>'GamesController@games'));
	Route::post('games',array('uses'=>'GamesController@games'));
	Route::get('games/create',array('uses'=>'GamesController@create'));
	Route::post('games/create',array('uses'=>'GamesController@store'));
	Route::get('games/edit/{request_id}',array('uses'=>'GamesController@game_edit'));
	Route::post('games/update',array('uses'=>'GamesController@game_update'));
	Route::post('games/delete_game/{request_id}',array('uses'=>'GamesController@delete_game'));
	Route::post('game/enable-disable',array('uses'=>'GamesController@enableDisableGame'));
	Route::post('game_image/delete/{request_id}',array('uses'=>'GamesController@deleteImage')); //Delete Image
	Route::get('game/image_downlad/{request_id}',array('uses'=>'GamesController@downloadImage')); //Download Image

	//Game guides
	Route::get('game-guides',array('uses'=>'GameGuidesController@guides'));
	Route::post('game-guides',array('uses'=>'GameGuidesController@guides'));
	Route::get('game-guides/create',array('uses'=>'GameGuidesController@create'));
	Route::post('game-guides/create',array('uses'=>'GameGuidesController@store'));
	Route::get('game-guides/edit/{request_id}',array('uses'=>'GameGuidesController@game_edit'));
	Route::post('game-guides/update',array('uses'=>'GameGuidesController@game_update'));
	Route::post('game-guides/delete_game/{request_id}',array('uses'=>'GameGuidesController@delete_game'));
	Route::post('game-guides/enable-disable',array('uses'=>'GameGuidesController@enableDisableGame'));
	Route::post('game-guide/image_delete/{request_id}',array('uses'=>'GameGuidesController@deleteImage')); //Delete Image
	Route::get('game-guide/image_downlad/{request_id}',array('uses'=>'GameGuidesController@downloadImage')); //Download Image

	//Access log
	Route::get('access-logs',array('uses'=>'AccessLogController@index'));
	route::get('access-logs/search',array('uses'=>'AccessLogController@search'))->name('accesslog.search');
	
	//Access Codes
Route::get('access-codes',array('uses'=>'AccessCodesController@main'));
Route::get('access-codes/manual',array('uses'=>'AccessCodesController@manual'));
Route::get('access-codes/auto',array('uses'=>'AccessCodesController@auto'));
Route::get('access-codes/create',array('uses'=>'AccessCodesController@create'));
route::post('access-codes/save',array('uses'=>'AccessCodesController@store'))->name('manual.save');
route::get('access-codes/search',array('uses'=>'AccessCodesController@search'))->name('manual.search');
Route::get('access-codes/edit/{id}',array('uses'=>'AccessCodesController@access_code_edit'));
Route::post('access-codes/delete/{id}',array('uses'=>'AccessCodesController@access_code_delete'));
route::get('access-codes/list',array('uses'=>'AccessCodesController@list'))->name('manual.used.list');
route::get('access-codes/list/search',array('uses'=>'AccessCodesController@search_used_list'))->name('used.list.search');
route::post('access-codes/list/delete/{id}',array('uses'=>'AccessCodesController@used_access_code_delete'))->name('used.list.delete');
route::get('access-codes/auto',array('uses'=>'AccessCodesController@auto'))->name('auto.used.list');
route::get('access-codes/auto/search',array('uses'=>'AccessCodesController@search_auto_list'))->name('used.auto.search');
				
	//Tournaments
	Route::get('tournaments',array('uses'=>'TournamentController@tournaments'));
	Route::post('tournaments',array('uses'=>'TournamentController@tournaments'));
	Route::get('tournaments/create',array('uses'=>'TournamentController@create'));
	Route::post('tournaments/create',array('uses'=>'TournamentController@store'));
	Route::get('tournaments/edit/{request_id}',array('uses'=>'TournamentController@tournament_edit'));
	Route::post('tournaments/update',array('uses'=>'TournamentController@tournament_update'));
	Route::post('tournaments/delete_game/{request_id}',array('uses'=>'TournamentController@delete_tournament'));
	Route::post('tournaments/enable-disable',array('uses'=>'TournamentController@enableDisableTournament'));
	
	//Webinar
	Route::get('webinar',array('uses'=>'WebinarController@webinars'));
	Route::post('webinar',array('uses'=>'WebinarController@webinars'));

	Route::get('webinar/create',array('uses'=>'WebinarController@create'));
	Route::post('webinar/create',array('uses'=>'WebinarController@store'));
	Route::get('webinar/edit/{request_id}',array('uses'=>'WebinarController@webinar_edit'));
	Route::post('webinar/update',array('uses'=>'WebinarController@webinar_update'));

	Route::get('webinar/delete_webinar/{webinar_id}',array('uses'=>'WebinarController@delete_webinar'));

	Route::post('webinar/webinar-disable',array('uses'=>'WebinarController@enableDisableWebinar'));









	



	// customers
	Route::get('customers',array('uses'=>'CustomersController@customers'));
	Route::post('customers',array('uses'=>'CustomersController@customers'));
	Route::post('update-customer/{request_id}', 'CustomersController@update_customer'); //Edit User
	Route::post('customer/edit/{request_id}', 'CustomersController@customer_edit'); //Edit User
	
	
	Route::get('customer/create/',array('uses'=>'CustomersController@customer_create')); //Edit User
	Route::post('create-new-customer', 'CustomersController@customer_create_new'); //Edit User
	Route::post('customer/delete_customer/{request_id}',array('uses'=>'CustomersController@customer_delete')); //Edit User
	Route::post('customer/mark_as_district_head/{request_id}',array('uses'=>'CustomersController@mark_as_district_head')); //Edit User
	Route::post('customer/mark_as_state_head/{request_id}',array('uses'=>'CustomersController@mark_as_state_head')); //Edit User
	Route::post('export_customers',array('uses'=>'CustomersController@export_customers')); //Edit User
	
	Route::post('export_users_customers/{id}',array('as'=>'ajax.pagination','uses'=>'UsersController@exportListingCustomers'));
	Route::post('export_users',array('as'=>'ajax.pagination1','uses'=>'UsersController@exportUsers'));
	
	Route::get('download-certificate/{request_id}',array('uses'=>'CustomersController@downloadCertificate')); //Edit User
	Route::get('manage-customer/{id}', 'CustomersController@manageCustomer');
	Route::post('customer/view/{request_id}', 'CustomersController@customer_view'); //Edit User
	
	Route::post('confirmModal', 'CommonController@confirmModal');
	
	Route::get('account', 'UsersController@account');
	
	Route::get('logout', 'UsersController@logout');
});	

Route::group(['prefix' => 'admin','as' => 'user.' ,'namespace' => 'User','middleware' => ['auth','admin']], function () {

	/*Chat module*/
	Route::get('chat', 'ChatController@index');
});

Route::group(['prefix' => 'admin','namespace' => 'Admin'], function () {
	Route::post('checklogin','AdminController@checklogin');
	// Route::post('sendpassword','AdminController@sendpassword');
	Route::get('/login', 'AdminController@login');
	// Route::get('forgotpassword', 'AdminController@forgotpassword');

});