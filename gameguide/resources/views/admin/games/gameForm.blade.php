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
{{-- Check if New Game or Edit game, if $newGame set 1 then new game else Edit Game --}}
@php
	$newGame = 1;
	$gameTitle = 'Add';
	$action = 'Add';
@endphp
@if(isset($game))
	@php
		$newGame = 2;
		$gameTitle = 'Edit';
		$action = 'Update';
	@endphp
@endif

@section('headtitle')
| {{$gameTitle}} Game
@endsection


	<div class="row">
		<div class="col-12">
			<h1>{{$gameTitle}} Game</h1>
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

								@if($newGame == 2)
									@if($errors->first('game_id'))
										<span class="error"> {{ $errors->first('game_id')  }} </span>
									@endif
								@endif	
					        
					        	@if($newGame == 1)
									{{ Form::open(array('url' => 'admin/games/create', 'method' => 'post','class'=>'profile form-horizontal','enctype'=>'multipart/form-data')) }}
								@else
									{{ Form::open(array('url' => 'admin/games/update/', 'method' => 'post','class'=>'profile form-horizontal','enctype'=>'multipart/form-data')) }}
								@endif


								<div class="form-group col-md-12">
									<div class="row">
										<div class="col-md-8 row col-xs-12">
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('title') }}
												@if($newGame == 1)
													{{ Form::text('title',old('title'),array('class'=>'form-control','placeholder'=>'Title')) }}
												@else
													{{ Form::text('title',old('title', $game->title),array('class'=>'form-control','placeholder'=>'Title')) }}
												@endif
													<span class="error"> {{ $errors->first('title')  }} </span>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('Image') }}

												<div class="clearfix"></div>
												<input id="fileupload" class="inputfile" type="file" name="image" accept="image/*">
												<label class="mt-2 mb-3" for="fileupload"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg> <span>Choose a file</span></label>

												@if($newGame == 2)
													@if($game->image != NULL)
													
														<div class="inputfile-preview old-file">
															<img src="{{$game->image_url}}" class="module_image game_new_image">
															<a href="{{url('/admin/game/image_downlad')}}/{{$game->id}}" class="filedownlad filedownlad-category">
																<i class="glyph-icon simple-icon-cloud-download"></i>
															</a>

															<a title="Delete Image"  data-id="{{ $game->id }}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Image?"  data-left_button_name ="Yes" data-left_button_id ="delete_game_image" data-left_button_cls="btn-primary" class="open_confirmBox action deleteImage"  href="javascript:void(0)" data-game_id="{{ $game->id }}">
																<i class="glyph-icon simple-icon-trash"></i>
															</a>
														</div>
													@endif
												@endif
												<div class="inputfile-preview new-file d-none">
													<img src="" class="module_image">
													<i class="glyph-icon simple-icon-trash new-module-file-trash"></i>
												</div>
												<span class="error"> {{ $errors->first('image')  }} </span> 
												
												{{--@if($newGame == 2)
													@if($game->image != NULL)
														<div class="clearfix"></div>
														<img src="{{$game->image_url}}" class="game_image" style="width:100px;">
														<div class="clearfix mb-4"></div>
													@endif
												@endif
												
												<input type="file" name="image" accept="image/*">--}}
												<span class="error"> {{ $errors->first('image')  }} </span>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('Short Description') }}
												@if($newGame == 1)
													{{ Form::textarea('short_description',old('short_description'),array('class'=>'form-control','placeholder'=>'Short Description','rows' => 3)) }}
												@else
													{{ Form::textarea('short_description',old('short_description', $game->short_description),array('class'=>'form-control','placeholder'=>'Short Description','rows' => 3)) }}
												@endif
													<span class="error"> {{ $errors->first('short_description')  }} </span>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('Description') }}
												@if($newGame == 1)
													{{ Form::textarea('description',old('description'),array('class'=>'form-control','placeholder'=>'')) }}
												@else
													{{ Form::textarea('description',old('description', $game->description),array('class'=>'form-control','placeholder'=>'')) }}
												@endif
												<span class="error"> {{ $errors->first('description')  }} </span>
											</div>
											<div class="clearfix"></div>
											
											<div class="col-md-12 col-xs-12 field mb-4">
												{{ Form::label('position') }}
												@if($newGame == 2)
													{{ Form::text('position',old('position', $game->position),array('class'=>'form-control','placeholder'=>'Position')) }}
												
												@endif
													<span class="error"> {{ $errors->first('position')  }} </span>
											</div>
											<div class="clearfix"></div>
										</div>

									</div>
								</div>
								

								<div class="form-group col-md-12">
									 <div class="sign-up-btn ">
									 	@if($newGame == 2)
											<input type="hidden" value="{{$game->id}}" name="game_id" id="game_id" >
										@endif
										 <input name="submit" class="loginmodal-submit btn btn-primary" id="game_update" value="{{$action}}" type="submit">
										 <a href="{{url('admin/games')}}" name="back" class="loginmodal-submit btn btn-primary" id="profile_back" value="Back" type="submit">Back</a>
									</div>
								</div>
							
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>
    @stop

@section('additionJs')
	<script src="{{ asset('js/module/games.js')}}"></script>	
@stop