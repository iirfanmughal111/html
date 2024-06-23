<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCodes;
use App\Models\AccessLog;
use App\Http\Requests\CreateAccessCodesRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UpdateAccessCodesRequest;
use Illuminate\Http\Request;
use Config;
use Response;
use DB;

class AccessLogController extends Controller
{
	
		public function index(){
		
			$records = AccessLog::all();
			
			$clients=DB::table('client_acess_log')->select('generation_client')->distinct()->orderBy('generation_client','asc')->get()->pluck('generation_client')->toArray();

			
		return view('admin.access_codes.access_log', ['records'=>$records,'clients'=>$clients,'selected'=>'']);
	}
	
	
	
		public function search(Request $request){
		$search = $request->get('search_number');
		$searchclient = $request->get('generation_client');	
		$like = '%'.$search.'%';
        $likeclient = '%'.$searchclient.'%';			
		$users = DB::table('client_acess_log');
			
			
		if($search)
		{
		$users=$users->where('access_code', 'like', $like);
			
		}	
			
			if($searchclient)
		{
				
		$users=$users->where('generation_client', 'like', $likeclient);
			
		}
			
			
			
		$users=$users->get();
		$clients=DB::table('client_acess_log')->select('generation_client')->distinct()->orderBy('generation_client','asc')->get()->pluck('generation_client')->toArray();
	
		return view('admin.access_codes.access_log', ['records'=>$users,'clients'=>$clients,'selected'=>$searchclient]);
	}
}
