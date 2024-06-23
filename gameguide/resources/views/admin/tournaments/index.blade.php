@extends('admin.layouts.admin')
@section('headtitle')
| Tournaments
@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Tournaments </h1>
			@if(check_role_access('tournament_create'))
				<span class="fl_right balance"><a id="create_user" class="btn btn-primary" href="{{'/admin/tournaments/create'}}">Create New Tournament</a></span>
			@endif
			<div class="separator mb-5"></div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-12 mb-4">
		
			@include('admin.partials.searchTournamentForm')
							
			<div class="card">
				<div class="card-body">
				<div class="table-responsive tournaments_full"  id="tag_container">
					 @include('admin.tournaments.tournamentsPagination')
				</div>
				</div>
			</div>

		</div>
	</div>
	<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>
@section('additionJs')
<script src="{{ asset('js/module/tournaments.js')}}"></script>	
@stop
@endsection