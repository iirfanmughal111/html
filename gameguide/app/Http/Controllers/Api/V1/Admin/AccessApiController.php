<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use Artisan;
use Illuminate\Http\Request;
class AccessApiController extends Controller
{
    public function store(Request $request)
    {
		//Artisan::call('config:clear');
	
		  AccessLog::create($request->all());
		
		return ['add'=>true];
	}
}