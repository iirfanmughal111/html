<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Requests\CreateGameGuideRequest;
use App\Http\Requests\UpdateGameGuideRequest;

use App\Models\GameGuide;
use App\Models\Game;
use App\Models\GuideType;
use App\Models\GameGuideTranscript;
use App\Models\GameGuideKey;
use Config;
use Session;
use Response;

class GameGuidesController extends Controller
{
    protected $per_page;
	public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
    	$this->game_path = public_path('/uploads/games');
    }

    public function guides(Request $request)
    {
    	access_denied_user('game_guides_listing');

    	$games = Game::get();
		$guideTypes = GuideType::get();

    	$guides_data = $this->game_guides_search($request,$pagination=true);
		if($guides_data['success']){
			$guides = $guides_data['guides'];
			$page_number =  $guides_data['current_page'];
			if(empty($page_number))
				$page_number = 1;
			
			if(!is_object($guides)) return $guides;
			if ($request->ajax()) {
				return view('admin.game_guides.gamesPagination', compact('guides','page_number'))->render();
			}
			return view('admin.game_guides.index',compact('guides','page_number','games','guideTypes'));	
		}else{
			return $guides_data['message'];
		}
    }

    public function game_guides_search($request,$pagination)
	{
		
		$page_number = $request->page;
		$number_of_records =$this->per_page;
		$title = trim($request->title);
		$description = trim($request->description);
		$game_id = trim($request->game_id);
		$guide_type_id = trim($request->guide_type_id);
		
		$result = GameGuide::where(`1`, '=', `1`);
			
		if($title !='' || $description !='' || $game_id !='' || $guide_type_id !=''){
			$title_q = '%' . $request->title .'%';
			// check title 
			if(isset($title) && !empty($title)){
				$result->where('title','LIKE',$title_q);
			} 
			
			$description_s = '%' . $description . '%';
			
			// check description 
			if(isset($description) && !empty($description)){
				$result->where('description','LIKE',$description_s);
			}

			//If Game id is selected 
			if(isset($game_id) && !empty($game_id)){
				$result->where('game_id',$game_id);
			}

			//If Guide Type is selected 
			if(isset($guide_type_id) && !empty($guide_type_id)){
				$result->where('guide_type_id',$guide_type_id);
			}
		}
		
		//echo $result->orderBy('created_at', 'desc')->toSql();die;
		
		if($pagination == true){
			$guides = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
		}else{
			$guides = $result->orderBy('created_at', 'desc')->get();
		}
		
		
		$data = array();
		$data['success'] = true;
		$data['guides'] = $guides;
		$data['current_page'] = $page_number;
		return $data;
	}

	/*Get request create view*/
	public function create(){
		$games = Game::get();
		$guideTypes = GuideType::get();

		return view('admin.game_guides.gameForm',compact('games','guideTypes'));
	}

	/*Store new Game*/
	public function store(CreateGameGuideRequest $request){
		$data =array();
		$data['title']	= trim($request->title);
		$data['short_description']	= trim($request->short_description);
		$data['description'] = trim($request->description); 
		$data['game_id'] = trim($request->game_id); 
		$data['guide_type_id'] = trim($request->guide_type_id);
		$data['embed_video'] = trim($request->embed_video);
		$data['guide_tag'] = ($request->guide_tag)?$request->guide_tag:"";
		
		$data['slug'] = CommonController::createSlug(trim($request->title),'gameGuide');
		$dat = GameGuide::create($data);

		$guide_id = $dat->id;
		$game_id = trim($request->game_id);

		$image = $request->file('image');
		$transcript = $request->transcript;
		$mykey = $request->mykey;

		$saveTranscript = $this->save_transcript($transcript,$guide_id);
		$saveKey = $this->save_key($mykey,$guide_id);

		if(!empty($image)){
			//print_r($image->getClientOriginalExtension());
			//print_r($image->getClientOriginalName());
			$new_name = rand() . '_game_guide_' . $image->getClientOriginalName();

			//CREATE Game FOLDER IF NOT 
			if (!is_dir($this->game_path)) {
				mkdir($this->game_path, 0777);
			}
			//CREATE Game ID FOLDER 
			$game_id_path = $this->game_path.'/'.$game_id;
			if (!is_dir($game_id_path)) {
				mkdir($game_id_path, 0777);
			}
			$image->move($game_id_path, $new_name);
			$gameUpdate = GameGuide::where('id',$guide_id);
			$imagedata = array();
			$imagedata['image'] = $new_name;
			$imagedata['original_image'] = $image->getClientOriginalName();
			$imagedata['mimes'] = trim($image->getClientOriginalExtension());		
		    $gameUpdate->update($imagedata);
		}

		Session::flash('success', 'Game Guide has been Created.');
		//return redirect('admin/game-guides/edit/'.$guide_id);
		return redirect('admin/game-guides');
	}

	/* Save data in transcript table*/
	public function save_transcript($transcript,$guide_id){
		if(!empty($transcript) && count($transcript) > 0 && !empty($guide_id)){
			$duration = $transcript['duration'];
			$content = $transcript['content'];

			//foreach check not empty duration and content
			foreach ($duration as $key => $dur) {
				$tran_dur = $dur;
				$tran_con = $content[$key];

				if(!empty(trim($tran_dur)) && !empty(trim($tran_con))){
					$data = array();
					$data['game_guide_id'] = $guide_id;
					$data['duration'] = $tran_dur;
					$data['content'] = $tran_con;
					GameGuideTranscript::create($data);
				}
			}
		}
	}

	/*Save keys*/
	public function save_key($keyNotes,$guide_id){
		if(!empty($keyNotes) && count($keyNotes)>0 && !empty($guide_id)){
			foreach ($keyNotes as $key => $note) {
				if(!empty(trim($note))){
					$data = array();
					$data['game_guide_id'] = $guide_id;
					$data['content'] = trim($note);
					GameGuideKey::create($data);
				}
			}
		}
		return true;
	}

	/*Edit Request View*/
	public function game_edit($id){
		access_denied_user('game_guides_edit');
		$guide = GameGuide::with('game','gameGuidetranscript','gameGuideKey')->where('id',$id)->first();
		$games = Game::get();
		$guideTypes = GuideType::get();
		return view('admin.game_guides.gameForm',compact('guide','games','guideTypes'));
	}

	/*Update Game*/
	public function game_update(UpdateGameGuideRequest $request){
		$guide_id = trim($request->guide_id);
		if(!empty($guide_id)){
			$data =array();
			$data['title']	= trim($request->title);
			$data['short_description']	= trim($request->short_description);
			$data['description'] = trim($request->description); 
			$data['game_id'] = trim($request->game_id); 
			$data['guide_type_id'] = trim($request->guide_type_id);
			$data['embed_video'] = trim($request->embed_video);
			$data['guide_tag'] = ($request->guide_tag)?$request->guide_tag:"";
			$game_id = trim($request->game_id);
			$image = $request->file('image');

			if(!empty($image)){
				//print_r($image->getClientOriginalExtension());
				//print_r($image->getClientOriginalName());
				$new_name = rand() . '_game_guide_' . $image->getClientOriginalName();

				//CREATE Game FOLDER IF NOT 
				if (!is_dir($this->game_path)) {
					mkdir($this->game_path, 0777);
				}
				//CREATE Game ID FOLDER 
				$game_id_path = $this->game_path.'/'.$game_id;
				if (!is_dir($game_id_path)) {
					mkdir($game_id_path, 0777);
				}
				$image->move($game_id_path, $new_name);
				
				$data['image'] = $new_name;
				$data['original_image'] = $image->getClientOriginalName();
				$data['mimes'] = trim($image->getClientOriginalExtension());
			}

			$gameUpdate = GameGuide::where('id',$guide_id);
			$gameUpdate->update($data);

			$transcript = $request->transcript;
			$mykey = $request->mykey;


			//Delete old, data
			GameGuideTranscript::where('game_guide_id',$guide_id)->delete();
			GameGuideKey::where('game_guide_id',$guide_id)->delete();
			

			$saveTranscript = $this->save_transcript($transcript,$guide_id);
			$saveKey = $this->save_key($mykey,$guide_id);

			//dd($game_id);

			Session::flash('success', 'Game Guide edit successfully.');

		}else{
			Session::flash('success', 'Something went wrong, please try again.');
		}
		return redirect('admin/game-guides/edit/'.$guide_id);
	}

	/*Delete Game*/
	public function delete_game(Request $request,$guide_id){
		access_denied_user('game_guides_delete');
		$data = [];
    	$data['success'] = false;
    	$data['message'] = 'Invalid Request';
		if($guide_id){
			$main_game  = GameGuide::where('id',$guide_id)->first();
			if($main_game){
				GameGuide::where('id',$guide_id)->delete();

				$guides_data = $this->game_guides_search($request,$pagination=true);
				$guides = [];
				$page_number = 1;
				if($guides_data['success']){
					$guides = $guides_data['guides'];
					$page_number =  $guides_data['current_page'];
					if(empty($page_number))
						$page_number = 1;
				}

				$data['success'] = true;
				$data['message'] = 'Successfully Delete Game Guides.';
				$data['view'] = view('admin.game_guides.gamesPagination', compact('guides','page_number'))->render();

			}else{
				$data['message'] = 'There is no Game Guide found.';
			}
		}
		return Response::json($data, 200);
	}

	public function enableDisableGame(Request $request){
		if($request->ajax()){
			$game = GameGuide::where('id',$request->guide_id);

			$data =array();
			$data['status'] =  $request->status;
			$game->update($data);
			
			// Show message on the basis of status 
			if($request->status==1)
			 $enable =true ;
			if($request->status==0)
			 $enable =false ;
		  
		   $result =array('success' => $enable);	
		   return Response::json($result, 200);
		}
	}

	/*Delete Image*/
	public function deleteImage($game_guide_id){
		$data = [];
    	$data['success'] = false;
    	$data['message'] = 'Invalid Request';
		if($game_guide_id){
			$main  = GameGuide::where('id',$game_guide_id)->firstOrFail();
			if(!empty($main)){
				$game = GameGuide::find($game_guide_id);
				$game->image='';
				$game->original_image='';
				$game->mimes='';
				$game->save();

				//unlink media
				$path = $this->game_path.'/'.$game_guide_id.'/'.$main->image;
				@unlink($path);

				$data['success'] = true;
				$data['message'] = 'Successfully Game Guide Image Delete.';
				

			}else{
				$data['message'] = 'There is no Game Guide found.';
			}
		}
		return Response::json($data, 200);
	}

	/*Download image*/
	public function downloadImage($game_id){
		if(!empty($game_id)){
			$game = GameGuide::where('id',$game_id)->firstOrFail();
			if(!empty($game)){
				//$path = $category->image_url;
				$path = $this->game_path.'/'.$game_id.'/'.$game->image;
				$headers = ['Content-Type' => $game->mimes];
				return Response::download($path, $game->original_image, $headers);
			}
		}
	}
}