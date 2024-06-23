@extends('admin.layouts.admin')
@section('content')
	@section('ckeditor')
	<script src="//cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
	<script>
	        CKEDITOR.replace( 'description',{
	    allowedContent: true
	} );
	</script>
	@stop
{{-- Check if New Tournament or Edit tournament, if $newTournament set 1 then new tournament else Edit Tournament --}}
@php
	$newTournament = 1;
	$tournamentTitle = 'Add';
	$action = 'Add'
@endphp
@if(isset($tournament))
	@php
		$newTournament = 2;
		$tournamentTitle = 'Edit';
		$action = 'Update'
	@endphp
@endif


@section('headtitle')
| {{$tournamentTitle}} Tournament
@endsection


	<div class="row">
		<div class="col-12">
			<h1>{{$tournamentTitle}} Tournament</h1>
			<div class="separator mb-5"></div>
		</div>
	</div>
   <!-- Main content -->
	<div class="card">
		<div class="card-body">
			<div class="table-responsive"  id="tag_container">
				<div class="col-lg-12">
					<div class="box box-primary">
						<div class="box-body">
							@include('flash-message')	

								@if($newTournament == 2)
									@if($errors->first('tournament_id'))
										<span class="error"> {{ $errors->first('tournament_id')  }} </span>
									@endif
								@endif	
					        
					        	@if($newTournament == 1)
									{{ Form::open(array('url' => 'admin/tournaments/create', 'method' => 'post','class'=>'profile form-horizontal','enctype'=>'multipart/form-data')) }}
								@else
									{{ Form::open(array('url' => 'admin/tournaments/update/', 'method' => 'post','class'=>'profile form-horizontal','enctype'=>'multipart/form-data')) }}
								@endif


								<div class="form-group col-md-12">
									<div class="row">
										<div class="col-md-8 row col-xs-12">
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('title') }}
												@if($newTournament == 1)
													{{ Form::text('title',old('title'),array('class'=>'form-control','placeholder'=>'Title')) }}
												@else
													{{ Form::text('title',old('title', $tournament->title),array('class'=>'form-control','placeholder'=>'Title')) }}
												@endif
													<span class="error"> {{ $errors->first('title')  }} </span>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('Image') }}
												{{--{{Form::file('image')}}  --}}
												
												@if($newTournament == 2)
													@if($tournament->image != NULL)
														<div class="clearfix"></div>
														<img src="{{$tournament->image_url}}" class="tournament_image" style="width:100px;">
														<div class="clearfix mb-4"></div>
													@endif
												@endif
												
												<input type="file" name="image" accept="image/*">
												<span class="error"> {{ $errors->first('image')  }} </span>
											</div>
											<div class="clearfix"></div>
											
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('link') }}
												@if($newTournament == 1)
													{{ Form::text('link',old('link'),array('class'=>'form-control','placeholder'=>'Link')) }}
												@else
													{{ Form::text('link',old('link', $tournament->link),array('class'=>'form-control','placeholder'=>'link')) }}
												@endif
													<span class="error"> {{ $errors->first('link')  }} </span>
											</div>	
										</div>
									</div>
								</div>

								<div class="form-group col-md-12">
									 <div class="sign-up-btn ">
									 	@if($newTournament == 2)
											<input type="hidden" value="{{$tournament->id}}" name="tournament_id" id="tournament_id" >
										@endif
										 <input name="submit" class="loginmodal-submit btn btn-primary" id="tournament_update" value="{{$action}}" type="submit">
										 <a href="{{url('admin/tournaments')}}" name="back" class="loginmodal-submit btn btn-primary" id="profile_back" value="Back" type="submit">Back</a>
									</div>
								</div>
							
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    @stop