<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Requests\CreateGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use Config;
use Session;
use Response;

class GamesController extends Controller
{
    protected $per_page;
	public function __construct()
    {
	   
        $this->per_page = Config::get('constant.per_page');
    	$this->game_path = public_path('/uploads/games');
    }

    public function games(Request $request)
    {
    	access_denied_user('game_listing');


    	$games_data = $this->game_search($request,$pagination=true);
		if($games_data['success']){
			$games = $games_data['games'];
			$page_number =  $games_data['current_page'];
			if(empty($page_number))
				$page_number = 1;
			
			if(!is_object($games)) return $games;
			if ($request->ajax()) {
				return view('admin.games.gamesPagination', compact('games','page_number'))->render();
			}
			return view('admin.games.index',compact('games','page_number'));	
		}else{
			return $games_data['message'];
		}
    }

    public function game_search($request,$pagination)
	{
		
		$page_number = $request->page;
		$number_of_records =$this->per_page;
		$title = $request->title;
		$description = $request->description;
		
		$result = Game::where(`1`, '=', `1`);
			
		if($title !='' || $description !=''){
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
		}
		
		//echo $result->orderBy('created_at', 'desc')->toSql();die;
		
		if($pagination == true){
			$games = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
		}else{
			$games = $result->orderBy('created_at', 'desc')->get();
		}
		
		
		$data = array();
		$data['success'] = true;
		$data['games'] = $games;
		$data['current_page'] = $page_number;
		return $data;
	}

	/*Get request create view*/
	public function create(){
		return view('admin.games.gameForm');
	}

	/*Store new Game*/
	public function store(CreateGameRequest $request){
		$data =array();
		$data['title']	= trim($request->title);
		$data['short_description']	= trim($request->short_description);
		$data['description'] = trim($request->description); 

		if(@$request->position !=''){
			$data['position'] = trim($request->position);
		}else{
			$maxValue = Game::max('position');
			$data['position'] = $maxValue+1;
		}
		 
		$data['slug'] = CommonController::createSlug(trim($request->title),'game');
		$dat = Game::create($data);

		$game_id = $dat->id;

		$image = $request->file('image');

		if(!empty($image)){
			//print_r($image->getClientOriginalExtension());
			//print_r($image->getClientOriginalName());
			$new_name = rand() . '_game_' . $image->getClientOriginalName();

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
			$gameUpdate = Game::where('id',$game_id);
			$imagedata = array();
			$imagedata['image'] = $new_name;
			$imagedata['original_image'] = $image->getClientOriginalName();
			$imagedata['mimes'] = trim($image->getClientOriginalExtension());			
		    $gameUpdate->update($imagedata);
		}

		//dd($game_id);

		Session::flash('success', 'Game has been Created.');
		//return redirect('admin/games/edit/'.$game_id);
		return redirect('admin/games');
	}

	/*Edit Request View*/
	public function game_edit($id){
		access_denied_user('game_edit');
		$game = Game::where('id',$id)->first();
		return view('admin.games.gameForm',compact('game'));
	}

	/*Update Game*/
	public function game_update(UpdateGameRequest $request){
		$game_id = trim($request->game_id);
		if(!empty($game_id)){
			$data =array();
			$data['title']	= trim($request->title);
			$data['short_description']	= trim($request->short_description);
			$data['description'] = trim($request->description);
			$data['position'] = trim($request->position); 
			$image = $request->file('image');

			if(!empty($image)){
				//print_r($image->getClientOriginalExtension());
				//print_r($image->getClientOriginalName());
				$new_name = rand() . '_game_' . $image->getClientOriginalName();

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

			$gameUpdate = Game::where('id',$game_id);
			$gameUpdate->update($data);

			//dd($game_id);

			Session::flash('success', 'Game edit successfully.');

		}else{
			Session::flash('success', 'Something went wrong, please try again.');
		}
		return redirect('admin/games/edit/'.$game_id);
	}

	/*Delete Game*/
	public function delete_game(Request $request,$game_id){
		access_denied_user('game_delete');
		$data = [];
    	$data['success'] = false;
    	$data['message'] = 'Invalid Request';
		if($game_id){
			$main_game  = Game::where('id',$game_id)->first();
			if($main_game){
				Game::where('id',$game_id)->delete();

				$games_data = $this->game_search($request,$pagination=true);
				$games = [];
				$page_number = 1;
				if($games_data['success']){
					$games = $games_data['games'];
					$page_number =  $games_data['current_page'];
					if(empty($page_number))
						$page_number = 1;
				}

				$data['success'] = true;
				$data['message'] = 'Successfully Delete complaint.';
				$data['view'] = view('admin.games.gamesPagination', compact('games','page_number'))->render();;

			}else{
				$data['message'] = 'There is no Game found.';
			}
		}
		return Response::json($data, 200);
	}

	public function enableDisableGame(Request $request){
		if($request->ajax()){
			$game = Game::where('id',$request->game_id);

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
	public function deleteImage($game_id){
		$data = [];
    	$data['success'] = false;
    	$data['message'] = 'Invalid Request';
		if($game_id){
			$main  = Game::where('id',$game_id)->firstOrFail();
			if(!empty($main)){
				$game = Game::find($game_id);
				$game->image='';
				$game->original_image='';
				$game->mimes='';
				$game->save();

				//unlink media
				$path = $this->game_path.'/'.$game_id.'/'.$main->image;
				@unlink($path);

				$data['success'] = true;
				$data['message'] = 'Successfully Game Image Delete.';
				

			}else{
				$data['message'] = 'There is no Game found.';
			}
		}
		return Response::json($data, 200);
	}

	/*Download image*/
	public function downloadImage($game_id){
		if(!empty($game_id)){
			$game = Game::where('id',$game_id)->firstOrFail();
			if(!empty($game)){
				//$path = $category->image_url;
				$path = $this->game_path.'/'.$game_id.'/'.$game->image;
				$headers = ['Content-Type' => $game->mimes];
				return Response::download($path, $game->original_image, $headers);
			}
		}
	}

}
