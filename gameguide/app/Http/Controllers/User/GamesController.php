<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GameGuide;

class GamesController extends Controller
{
    public function index()
    {
 
        //$games = Game::orderBy('created_at', 'desc')->get();
        $games = Game::orderBy('position', 'asc')->get();
        $posts =Game::all();
		foreach($posts as $post){

			
			$newPost = $post->replicate();
			$newPost->lang_code = 'ar';
			//$post->delete();
		
		   $newPost->save();
           $saved = Game::latest()->first();          
			
			if (!is_dir(public_path('/uploads/games/'.$saved->id))) {
				mkdir(public_path('/uploads/games/'.$saved->id), 0777);
				\File::copy(public_path('/uploads/games/'.$post->id.'/'.$post->image),public_path('/uploads/games/'.$saved->id.'/'.$post->image));
			}
							
		}
		
       return view('frontend.creaters.game.index',compact('games'));
    }

    public function details($slug=''){
        if(!empty($slug)){
            $game = Game::with('gameGuide')->where('slug',$slug)->first();
            return view('frontend.creaters.game.details',compact('game'));
        }else{
            return view('frontend.creaters.game.details');
        }
    }

    public function guide_details($type='',$slug=''){
		

        if(!empty($slug)){
            $gameGuide = GameGuide::with('guideType','gameGuidetranscript','gameGuideKey')->where('slug',$slug)->first();
            return view('frontend.creaters.game.guide_details',compact('gameGuide'));
        }else{
            return view('frontend.creaters.game.guide_details');
        }
    }
	
	public function member(){
		
		return view('frontend.creaters.account.member');
	}
}