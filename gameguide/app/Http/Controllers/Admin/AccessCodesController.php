<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCodes;
use App\Http\Requests\CreateAccessCodesRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UpdateAccessCodesRequest;
use Illuminate\Http\Request;
use Config;
use Response;
use DB;

class AccessCodesController extends Controller
{

	public function main(){
		
		return view('admin.access_codes.access_CodesMenu');
	}

	public function manual(){

		$records = DB::select('select * from add_access_codes where is_manual = 1 AND user_id = 0 order by serial_id DESC');
		return view('admin.access_codes.access_CodesManual', ['records'=>$records]);
	}

	public function create(){
		$check = false;
		return view('admin.access_codes.access_CodesM_Form', ["check"=>$check]);
	}

	public function store(CreateAccessCodesRequest $request){

		$getupdate = $request->input('old_id');

		if($getupdate){

			$number = $request->input('number');
			DB::update('update add_access_codes set number = ? where serial_id = ?',[$number, $getupdate]);
	
			Session::flash('success', 'Access Code edited successfully.');
			return redirect('admin/access-codes/manual');

		}

		If(!empty($request)){
			$accessCodesArray = explode("\n", str_replace(["\r\n", "\n\r", "\r"], "\n", $request->number));

		foreach($accessCodesArray as $accessCode)
		{
			$check = DB::select('select * from add_access_codes where number = ?', [$accessCode]);
	        if(count($check) > 0)
		{
			Session::flash('danger', 'Access Code Already Exist.');
		    return redirect('admin/access-codes/create');
		}
		else
		{
			$code = new AccessCodes();
			$code->number = $accessCode;
			$code->is_manual = 1;
			$code->save();
			Session::flash('sucess', 'Access Code Succesfully Created.');
		}
		
		}	
		return back();
		// return redirect('admin/access_codes/manual')->with('status', 'Successfully Creaated!');
		Session::flash('sucess', 'Access Code Succesfully Created.');
		
		// return redirect('admin/access_codes/manual');	
	}
	}

	public function access_code_edit($id){

		$check = true;
		
		$getupdte = DB::select('select * from add_access_codes where  serial_id = ?',[$id]);

		return view('admin.access_codes.access_CodesM_Form', ['getupdte'=>$getupdte,"check"=> $check]);
	}

	public function access_code_delete(Request $request,$access_code_id){
	
		access_denied_user('game_delete');
		$data = [];
    	$data['success'] = false;
    	$data['message'] = 'Invalid Request';
		if($access_code_id > 0){
			if($access_code_id > 0){
				DB::delete('delete from add_access_codes where serial_id  = ?',[$access_code_id]);
				$records = DB::select('select * from add_access_codes order by serial_id DESC');
	
				$data['success'] = true;
				$data['message'] = 'Successfully Delete Used Code.';
				$data['view'] = view('admin.access_codes.Access_Codes_Table', ['records'=>$records])->render();

			}else{
				$data['message'] = 'There is no record found.';
			}
		}
		
		return Response::json($data, 200);
	}

	public function search(Request $request){
		$search = $request->get('search_number');
		$like = '%'.$search.'%';
		$users = DB::table('add_access_codes')
		->where('is_manual', '=', 1)
		->where('number', 'like', $like)
		->get();
		return view('admin.access_codes.access_CodesManual', ['records'=>$users]);
	}

	public function list(){

		$usedcodes = DB::select('SELECT ac.number, u.first_name,u.last_name,ac.used_date,ac.serial_id FROM add_access_codes as ac INNER JOIN users as u ON ac.user_id=u.id where user_id != 0 ');

		return view('admin.access_codes.used_list.used_list_AccessCode_Table', ['usedcodes'=>$usedcodes]);
	}

	public function search_used_list(Request $request){
		$search = $request->get('search_used');
		$like = '%'.$search.'%';
		$used_users = DB::table('add_access_codes')
		->join('users', 'users.id', '=', 'add_access_codes.user_id')
		->where('user_id', '!=', 0)
		->where('number','like', $like)
		->get();
		return view('admin.access_codes.used_list.used_list_AccessCode_Table', ['usedcodes'=>$used_users]);
	}

	public function used_access_code_delete(Request $request,$used_access_code_id){
	
		access_denied_user('game_delete');
		$data = [];
    	$data['success'] = false;
    	$data['message'] = 'Invalid Request';
		if($used_access_code_id > 0){
			if($used_access_code_id > 0){
				DB::delete('delete from add_access_codes where serial_id  = ?',[$used_access_code_id]);
				$usedcodes = DB::select('select * from add_access_codes order by serial_id DESC');
	
				$data['success'] = true;
				$data['message'] = 'Successfully Delete Used Code.';
				$data['view'] = view('admin.access_codes.used_list.used_list_AccessCode_Table', ['usedcodes'=>$usedcodes])->render();

			}else{
				$data['message'] = 'There is no record found.';
			}
		}
		return Response::json($data, 200);
	}

	public function auto(){

		$auto_records = DB::select('SELECT ac.number, u.first_name, u.last_name, ac.used_date, ac.serial_id 
		FROM add_access_codes as ac INNER JOIN users as u ON ac.user_id=u.id where user_id != 0 AND is_manual = 0');

		return view('admin.access_codes.auto.Auto_list_AccessCode_Table', ['auto_records'=>$auto_records]);
	}

	public function search_auto_list(Request $request){
		$search_auto = $request->get('search_auto');
		$like = '%'.$search_auto.'%';
		$auto_used = DB::table('add_access_codes')
		->join('users', 'users.id', '=', 'add_access_codes.user_id')
		->where('user_id', '>', 0)
		->where('is_manual', '=', 0)
		->where('number','like', $like)
		->get();
		return view('admin.access_codes.auto.Auto_list_AccessCode_Table', ['auto_records'=>$auto_used]);
	}
}