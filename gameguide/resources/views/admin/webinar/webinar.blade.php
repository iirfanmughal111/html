@extends('admin.layouts.admin')
@section('headtitle')
| Webinars
@endsection
@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Webinars </h1>
			@if(check_role_access('webinar_create'))
				<span class="fl_right balance"><a id="create_webinar" class="btn btn-primary" href="{{'/admin/webinar/create'}}">Create New Webinar</a></span>
			@endif
			<div class="separator mb-5"></div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-12 mb-4">
		@include('flash-message')

        @include('admin.partials.searchWebinarForm')
							
			<div class="card">
				<div class="card-body">
				<div class="table-responsive webinar_full"  id="tag_container">
				@include('admin.webinar.webinarsPagination')

				</div>
				</div>
			</div>

		</div>
	</div>
	<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>
@section('additionJs')
<script src="{{ url('js/module/webinar.js')}}"></script>	
@stop
@endsection