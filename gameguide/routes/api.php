<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Admin\UnSubscribeApiPremium;


Route::group(['prefix' => 'v1', 'as' => 'admin.', 'namespace' => 'Api\V1\Admin'], function () {
    Route::apiResource('permissions', 'PermissionsApiController');

    Route::apiResource('roles', 'RolesApiController');

    Route::apiResource('users', 'UsersApiController');

    Route::apiResource('products', 'ProductsApiController');
	
	Route::apiResource('access-log', 'AccessApiController');
	
	
});

Route::group(['prefix' => 'v1'], function () {

    
 Route::get("un-sub","Api\V1\Admin\UnSubscribeApiPremium@getdata");
});


Route::group(['prefix' => 'v1'], function () {

    
    Route::get("cp","Api\V1\ClientPanel\ClientPanel@totalAccessCode");
	Route::get("cp-graph","Api\V1\ClientPanel\ClientPanel@graph");
  
	Route::get("cp-latestCode","Api\V1\ClientPanel\ClientPanel@latestCode");
    Route::get("cp-accessCode","Api\V1\ClientPanel\ClientPanel@accessCode");
	Route::get("cp-editAccessCode","Api\V1\ClientPanel\ClientPanel@editAccessCode");
	Route::get("cp-deleteAccessCode","Api\V1\ClientPanel\ClientPanel@deleteAccessCode");
	Route::get("cp-exportCodes","Api\V1\ClientPanel\ClientPanel@exportCodes");
	Route::get("cp-exportlog","Api\V1\ClientPanel\ClientPanel@exportlog");
	Route::get("cp-accessLog","Api\V1\ClientPanel\ClientPanel@accessLog");
	Route::get("cp-allAccessCode","Api\V1\ClientPanel\ClientPanel@allAccessCode");
	Route::get("cp-monthlyAccessCode","Api\V1\ClientPanel\ClientPanel@MontlyAccessCode");
	Route::get("cp-todayAccessCode","Api\V1\ClientPanel\ClientPanel@TodayAccessCode");
	
});
