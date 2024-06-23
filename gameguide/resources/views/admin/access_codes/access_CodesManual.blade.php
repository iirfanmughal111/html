@extends('admin.layouts.admin')
@section('headtitle')
| Manual Access Codes
@endsection

@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Manual Access Codes</h1>
			
				<span class="fl_right balance"><a id="create_user" class="btn btn-primary" href="{{'/admin/access-codes/create'}}">Create Access Code</a></span>
			
			<div class="separator mb-5"></div>
		</div>	
	</div>

	{{-- search Part  --}}

	<form action="{{ route('manual.search') }}" method ='GET' class='profile form-horizontal' enctype='multipart/form-data'>
		<div class="row">
			<div class="col-md-12 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="form-group col-lg-8">
										<input type="search" name="search_number" id="title" class="form-control" placeholder="Search By Access Code">
									</div>
									
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-6">
								<button type="submit" name="search" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">Search</button>
								<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
	
								<a href="{{url('admin/access-codes')}}" name="back" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto" value="Back" type="submit">Back</a>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>	
</form>
	<br>
	<br>

<!-- Table View Of Access Codes -->

  <!-- Main content -->
  <div class="card">
	<div class="card-body">
		<div class="table-responsive"  id="tag_container">
			<div class="col-lg-12">
				<div class="box box-primary">
					<div class="box-body">
						@include('flash-message')
                            <div id="rtable">
                                 <table class="table table-hover">
	                                 <tr class="thead bg-primary">
		                                <th>Access Codes</th>
		                                    <th width="500px">Action</th>
	                                            </tr>
	                                                @if(!count($records) < 1)
	                                                   @foreach ($records as $record)
	                                                       <tr class="thead-light">
		                                                     <td>{{ $record->number }}</td>	
		                                                         <td>
																	<form action="" method="POST">
				                                                   <div class="form-group col-md-12">
					                                                     <div class="sign-up-btn ">
						                                          <a title="Edit Access Code" class="access_code_edit" href="edit/{{$record->serial_id}}" title="Edit Access Code">
						                                          	<i class="simple-icon-note"></i>
						                                            </a>
						                                            @csrf @method('Delete')
						                                         <a title="Delete Access Code"  data-id="{{$record->serial_id}}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Access Code?"  data-left_button_name ="Yes" data-left_button_id ="delete_access" data-left_button_cls="btn-primary" class="open_confirmBox action access_code_delete"  href="javascript:void(0)" data-game_id="{{$record->serial_id}}">
							                                     <i class="glyph-icon simple-icon-trash"></i>
						                                        </a>
			                                                   </div>	
			                                                   </form> 
		                                                      </td>
	                                                      </tr>
	@endforeach
	@else
	<tr class="thead-light">
		<td>
				<h3>Not Have Any Record !!!</h3>				
		</td>
	</tr>
	@endif
		
	
	
                                    </table>
                                </div>
							</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>
@section('additionJs')
<script src="{{ asset('js/module/access_codes.js')}}"></script>
@stop
@endsection
