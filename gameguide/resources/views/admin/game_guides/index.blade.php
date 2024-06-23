@extends('admin.layouts.admin')
@section('headtitle')
| Games Guides
@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Game Guides </h1>
			@if(check_role_access('game_guides_create'))
				<span class="fl_right balance"><a id="create_user" class="btn btn-primary" href="{{'/admin/game-guides/create'}}">Create New Game Guides</a></span>
			@endif
			<div class="separator mb-5"></div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-12 mb-4">
		
			@include('admin.partials.searchGameGuideForm')
							
			<div class="card">
				<div class="card-body">
				<div class="table-responsive game_guides_full"  id="tag_container">
					 @include('admin.game_guides.gamesPagination')
				</div>
				</div>
			</div>

		</div>
	</div>
	<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>
@section('additionJs')
<script src="{{ asset('js/module/guides.js')}}"></script>	
@stop
@endsection