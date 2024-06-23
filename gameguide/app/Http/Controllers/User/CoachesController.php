<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\CreateUserRatingRequest;
use App\Models\User;
use App\Models\CoacheRating;
use Config;
use Response;
use Auth;

class CoachesController extends Controller
{
    public function index()
    {
    	$role_id = Config::get('constant.role_id');
    	$query = User::where('role_id',$role_id['COACHE_USER'])->where('status',1);
        if (Auth::check()) {
            $loginUserId = Auth::user()->id;

            $query->where('id','!=',$loginUserId);
        }
        $coaches = $query->get();
       	return view('frontend.creaters.coaches.index',compact('coaches'));
    }

    public function details($coache_hash){
    	if(!empty($coache_hash)){
	    	$coache = User::with('coacheRating','userProfile')->where('hash',$coache_hash)->first();
            $coache_id = $coache->id;
            if($coache){
                /*Check this user review before for same coach, if yes then disable review section*/
                $isUserReview = 0;
                if (Auth::check()) {
                    $loginUserId = Auth::user()->id;
                    $ratingCheck = CoacheRating::where('user_id',$loginUserId)->where('coache_id',$coache_id)->first();
                    if($ratingCheck)
                        $isUserReview = 1;
                }
                return view('frontend.creaters.coaches.details',compact('coache','isUserReview'));
            }else{
               return redirect('/coaches'); 
            }
	    }else{
	    	return redirect('/coaches');
	    }
    }

    public function userRating(CreateUserRatingRequest $request){
        $data = [];
        $data['success'] = false;
        $data['message'] = 'Invalid Request';
        if(!empty(trim($request->user_id)) && !empty(trim($request->coache_id))){
            /*Again check if user not review yet, then give rating*/
            $ratingCheck = CoacheRating::where('user_id',$request->user_id)->where('coache_id',$request->coache_id)->first();
            if($ratingCheck){
                $data['message'] = 'Already you done rating, so this will not store in our system';
            }else{
                $data =array();
                $ratingData['user_id']  = trim($request->user_id);
                $ratingData['coache_id']  = trim($request->coache_id);
                $ratingData['rating'] = trim($request->rating); 
                $ratingData['comment'] = trim($request->comment); 
                $dat = CoacheRating::create($ratingData);

                $data['message'] = 'Successfully Add Rating.';
            }


            $coache = User::where('id',$request->coache_id)->first();
            $isUserReview = 1;
            $data['success'] = true;
            
            $data['view'] = view("frontend.partials.coache_rating",compact('coache','isUserReview'))->render();
        }
        return Response::json($data, 200);

    }
}
