@extends('admin.layouts.admin')
@section('headtitle')
| Games
@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Games </h1>
			@if(check_role_access('game_create'))
				<span class="fl_right balance"><a id="create_user" class="btn btn-primary" href="{{'/admin/games/create'}}">Create New Game</a></span>
			@endif
			<div class="separator mb-5"></div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-12 mb-4">
		
			@include('admin.partials.searchGameForm')
							
			<div class="card">
				<div class="card-body">
				<div class="table-responsive games_full"  id="tag_container">
					 @include('admin.games.gamesPagination')
				</div>
				</div>
			</div>

		</div>
	</div>
	<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>
@section('additionJs')
<script src="{{ asset('js/module/games.js')}}"></script>	
@stop
@endsection