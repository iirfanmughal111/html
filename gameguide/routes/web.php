<?php

//Route::get('/', 'User\UsersController@landing_page');

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');


Route::post('lang-switch','User\LanguageController@languageSwitch');

Route::group(['middleware' => ['AutoLogin','RegisterUserCode']], function () {

Route::get('/', function () {
    return redirect('login');
});
// Route::get('admin/webinar/email', function () {
//     return view('admin.webinar.email');
// });


Route::get('clear', function () {
	
	// \Artisan::call('migrate --path=/database/migrations/2022_06_13_084940_create_notifications_table.php');
    dd('migrated!');
});



Auth::routes(['login' => true]);
Auth::routes(['register' => true]);

Route::get('/redirect', 'SocialAuthFacebookController@redirect');
Route::get('/callback', 'SocialAuthFacebookController@callback');

Route::post('check_register','User\UsersController@checkRegister');

Route::post('register', 'Auth\RegisterController@register');

Route::group(['prefix' => '','as' => 'user.' ,'namespace' => 'User','middleware' => ['auth','user']], function () {
    //FACEBOOK LOGIN 
	
	//GOOGLE LOGIN 
	Route::get('/redirectg/{role}', 'SocialAuthGoogleController@redirect');
	Route::get('/callbackg', 'SocialAuthGoogleController@callback');
	
	//TWITTER  LOGIN 
	Route::get('/twitter/redirect/{role}', 'SocialAuthTwitterController@redirect');
	Route::get('twitter/callback', 'SocialAuthTwitterController@callback');
	
Route::group(['middleware' => ['userStatus']], function () {
	
	Route::get('user-profile', 'UsersController@editProfile'); 
	
	Route::get('members', 'UsersController@member'); 
	
	//Route::get('edit-profile', 'UsersController@editProfile'); 
	Route::get('logout', 'UsersController@logout');
	Route::post('edit_social_link','UsersController@editSocailLink');
	Route::post('edit_description','UsersController@editDescription');
	

	Route::get('game-guide', 'GamesController@index');
	Route::get('game-guide/{slug?}', 'GamesController@details');
	Route::get('game-guide/guide/{type?}/{slug?}', 'GamesController@guide_details');
	Route::get('game-details', 'GamesController@details'); 
	Route::get('guide-details','GamesController@guide_details');
	//Webinars
	Route::get('webinars/{webinar_id?}', 'WebinarController@UserSideWebinars');
	Route::get('webinars/play/{webinar_id}', 'WebinarController@playWebinars');
	Route::get('webinars/coach-webinars/{coach_id}', 'WebinarController@coachWebinars');
	Route::get('webinars/about-user/{user_id}', 'WebinarController@userProfile');
	Route::post('webinars/watching-count', 'WebinarController@watchingCount');
	Route::post('webinars/registration', 'WebinarController@registration');
	Route::post('webinars/CancelRegistration', 'WebinarController@CancelRegistration');





	Route::get('coaches', 'CoachesController@index'); 
	Route::get('coache-details/{request_id}', 'CoachesController@details');
	Route::post('coache/user-rating','CoachesController@userRating');

	Route::get('plans', 'PlansController@index');
	Route::post('update-plan','PlansController@updatePlan');

	Route::get('tournament','TournamentController@index');
	
	Route::post('update-profile', 'UsersController@UpdateEditProfile')->name('update-profile'); 
	Route::post('changepassword', 'UsersController@passwordUpdate'); 
	Route::post('upload_profile_photo', 'UsersController@uploadProfilePhoto'); 
	Route::post('upload_banner_photo', 'UsersController@uploadBannerPhoto');
	Route::post('edit_profile_name','UsersController@edit_profile_name');
	Route::post('edit_profile_tag','UsersController@edit_profile_tag');



	/*Chat module*/
	Route::get('chat', 'ChatController@index');
	Route::post('chat-subscribe', 'ChatController@userSubscibe');
	
	/*liveupdate module*/
	 Route::get('live-update', 'LiveUpdate@index');
	
	/*playercommunity module*/
	Route::get('players', 'PlayerCommunity@index');
	
	
	/*chat specific user*/
	Route::get('manage-user/{id}', 'PlayerCommunity@manageUser');
	
		/*Friend request and notification*/
	Route::get('user-notifications', 'UserFriendController@userNotifications');
    Route::get('add-frnd/{id}', 'UserFriendController@addfriend');
	Route::get('accept-frnd/{id}', 'UserFriendController@acceptfriend');
	Route::get('cancel-frnd/{id}', 'UserFriendController@cancelfriendRequest');
	Route::get('reject-frnd/{id}', 'UserFriendController@rejectfriendRequest');
	Route::get('un-frnd/{id}', 'UserFriendController@unfriend');
	
});
  	/*Route::get('/contacts', 'ChatController@get');
	
	
  	Route::get('/conversation/{id}', 'ChatController@getMessagesFor');
	Route::post('/conversation/send', 'ChatController@send');
	Route::get('/notification/message', 'ChatController@notificationMessage');
	Route::get('/unreadcount/{id}', 'ChatController@unreadcountStatus');
	Route::get('/getLatestMessage','ChatController@getLatestMessage');

	Route::post('broadcasting/auth', ['uses' => '\Illuminate\Broadcasting\BroadcastController@authenticate']);*/
});

Route::group(['middleware' => ['userStatus']], function () {

	Route::get('members', 'User\GamesController@member'); 
	Route::get('/contacts', 'User\ChatController@get');
	Route::get('/conversation/{id}', 'User\ChatController@getMessagesFor');
	Route::post('/conversation/send', 'User\ChatController@send');
	Route::get('/notification/message', 'User\ChatController@notificationMessage');
	Route::get('/unreadcount/{id}/{type?}', 'User\ChatController@unreadcountStatus');
	Route::get('/getLatestMessage','User\ChatController@getLatestMessage');

	Route::post('broadcasting/auth', ['uses' => '\Illuminate\Broadcasting\BroadcastController@authenticate']);

	Route::post('user/cityDropdown', 'User\UsersController@cityDropdown');
	Route::post('user/calculateAge', 'User\UsersController@calculateAge');
	Route::post('user/verifiedAadhar', 'User\UsersController@verifiedAadhar');
	
	
	
	  
	
 });
	
 });
	
	Route::get('verify/account/{token}', 'Auth\RegisterController@verifyAccount'); //VERIFY ACCOUNT


Route::get('confirmation', 'Auth\RegisterController@Registeration_confirmation'); //REGISTER CONFIRMATION

// Password Reset Routes...
Route::post('send_reset_link', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::post('password/reset_new_user_password', 'Auth\ResetPasswordController@reset_new_user_password');


/* Route::get('verify/account/{token}', 'User\UsersController@verifyAccount'); //VERIFY ACCOUNT


// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
Route::post('password/reset_new_user_password', 'Auth\ResetPasswordController@reset_new_user_password'); */