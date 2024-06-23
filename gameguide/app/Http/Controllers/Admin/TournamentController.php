<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTournamentRequest;
use App\Http\Requests\UpdateTournamentRequest;
use App\Models\Tournament;
use Config;
use Session;
use Response;

class TournamentController extends Controller
{
    protected $per_page;
	public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
    	$this->tournament_path = public_path('/uploads/tournament');
    }

    public function tournaments(Request $request)
    {
    	access_denied_user('tournament_listing');

    	$tournaments_data = $this->tournament_search($request,$pagination=true);
		if($tournaments_data['success']){
			$tournaments = $tournaments_data['tournaments'];
			$page_number =  $tournaments_data['current_page'];
			if(empty($page_number))
				$page_number = 1;
			
			if(!is_object($tournaments)) return $tournaments;
			if ($request->ajax()) {
				return view('admin.tournaments.tournamentsPagination', compact('tournaments','page_number'))->render();
			}
			return view('admin.tournaments.index',compact('tournaments','page_number'));	
		}else{
			return $tournaments_data['message'];
		}
    }

    public function tournament_search($request,$pagination)
	{
		
		$page_number = $request->page;
		$number_of_records =$this->per_page;
		$title = $request->title;
		$description = $request->description;
		
		$result = Tournament::where(`1`, '=', `1`);
			
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
			$tournaments = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
		}else{
			$tournaments = $result->orderBy('created_at', 'desc')->get();
		}
		
		
		$data = array();
		$data['success'] = true;
		$data['tournaments'] = $tournaments;
		$data['current_page'] = $page_number;
		return $data;
	}

	/*Get request create view*/
	public function create(){
		return view('admin.tournaments.tournamentForm');
	}

	/*Store new Tournament*/
	public function store(CreateTournamentRequest $request){
		$data =array();
		$data['title']	= trim($request->title);
		$data['link']	= trim($request->link);
		
		$dat = Tournament::create($data);

		$tournament_id = $dat->id;

		$image = $request->file('image');

		if(!empty($image)){
			$new_name = rand() . '_tournament_' . $image->getClientOriginalName();

			//CREATE Tournament FOLDER IF NOT 
			if (!is_dir($this->tournament_path)) {
				mkdir($this->tournament_path, 0777);
			}
			//CREATE Tournament ID FOLDER 
			$tournament_id_path = $this->tournament_path.'/'.$tournament_id;
			if (!is_dir($tournament_id_path)) {
				mkdir($tournament_id_path, 0777);
			}
			$image->move($tournament_id_path, $new_name);
			$tournamentUpdate = Tournament::where('id',$tournament_id);
			$imagedata = array();
			$imagedata['image'] = $new_name;			
		    $tournamentUpdate->update($imagedata);
		}

		//dd($tournament_id);

		Session::flash('success', 'Tournament has been Created.');
		return redirect('admin/tournaments/edit/'.$tournament_id);
	}

	/*Edit Request View*/
	public function tournament_edit($id){
		access_denied_user('tournament_edit');
		$tournament = Tournament::where('id',$id)->first();
		return view('admin.tournaments.tournamentForm',compact('tournament'));
	}

	/*Update Tournament*/
	public function tournament_update(UpdateTournamentRequest $request){
		$tournament_id = trim($request->tournament_id);
		if(!empty($tournament_id)){
			$data =array();
			$data['title']	= trim($request->title);
			$data['link']	= trim($request->link);

			$image = $request->file('image');

			if(!empty($image)){
				//print_r($image->getClientOriginalExtension());
				//print_r($image->getClientOriginalName());
				$new_name = rand() . '_tournament_' . $image->getClientOriginalName();

				//CREATE Tournament FOLDER IF NOT 
				if (!is_dir($this->tournament_path)) {
					mkdir($this->tournament_path, 0777);
				}
				//CREATE Tournament ID FOLDER 
				$tournament_id_path = $this->tournament_path.'/'.$tournament_id;
				if (!is_dir($tournament_id_path)) {
					mkdir($tournament_id_path, 0777);
				}
				$image->move($tournament_id_path, $new_name);
				
				$data['image'] = $new_name;
			}

			$tournamentUpdate = Tournament::where('id',$tournament_id);
			$tournamentUpdate->update($data);

			//dd($tournament_id);

			Session::flash('success', 'Tournament edit successfully.');

		}else{
			Session::flash('success', 'Something went wrong, please try again.');
		}
		return redirect('admin/tournaments/edit/'.$tournament_id);
	}

	/*Delete Tournament*/
	public function delete_tournament(Request $request,$tournament_id){
		access_denied_user('tournament_delete');
		$data = [];
    	$data['success'] = false;
    	$data['message'] = 'Invalid Request';
		if($tournament_id){
			$main_tournament  = Tournament::where('id',$tournament_id)->first();
			if($main_tournament){
				Tournament::where('id',$tournament_id)->delete();

				$tournaments_data = $this->tournament_search($request,$pagination=true);
				$tournaments = [];
				$page_number = 1;
				if($tournaments_data['success']){
					$tournaments = $tournaments_data['tournaments'];
					$page_number =  $tournaments_data['current_page'];
					if(empty($page_number))
						$page_number = 1;
				}

				$data['success'] = true;
				$data['message'] = 'Successfully Delete complaint.';
				$data['view'] = view('admin.tournaments.tournamentsPagination', compact('tournaments','page_number'))->render();;

			}else{
				$data['message'] = 'There is no Tournament found.';
			}
		}
		return Response::json($data, 200);
	}

	public function enableDisableTournament(Request $request){
		if($request->ajax()){
			$tournament = Tournament::where('id',$request->tournament_id);

			$data =array();
			$data['status'] =  $request->status;
			$tournament->update($data);
			
			// Show message on the basis of status 
			if($request->status==1)
			 $enable =true ;
			if($request->status==0)
			 $enable =false ;
		  
		   $result =array('success' => $enable);	
		   return Response::json($result, 200);
		}
	}
}
