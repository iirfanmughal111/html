<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;

class TournamentController extends Controller
{
	public function index()
    {
    	$tournaments = Tournament::get();
       return view('frontend.creaters.tournament.index',compact('tournaments'));
    }

}

?>