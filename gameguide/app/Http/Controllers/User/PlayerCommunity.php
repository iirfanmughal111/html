<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\UserFriend;
class PlayerCommunity extends Controller
{
    
    public function index(Request $request){

			$users = User::with('userProfile')->where('id','!=' , Auth::user()->id)->where('role_id','=' , 2)->paginate(12);

	
			return view('frontend.playercommunity.users',compact('users'));
		
    }
    
    public function manageUser($id){



	if(Auth::check()){


	  $userFirst = UserFriend::
        where('user_request', '=',$id)
      ->where('user_accept', '=', Auth::user()->id)->where('status','=','1')->first();

	  $userSecond = UserFriend::
	  where('user_request', '=',Auth::user()->id)
	  ->where('user_accept', '=', $id)->where('status','=','1')->first();


		$cahtWithUser = User::where('id', $id)->first();
				
			if($cahtWithUser && ($userFirst || $userSecond )){


					Session::put('chat_with_user_id',$id);
					return view('frontend.playercommunity.chat');
			}else{

				abort(403, 'You are not to Allowed' );
			}



			
	}else{

			return redirect('/login');
	}
		
		


	}
     


}
