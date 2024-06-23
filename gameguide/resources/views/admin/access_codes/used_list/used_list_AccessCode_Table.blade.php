@extends('admin.layouts.admin')
@section('headtitle')
| Used Access Codes
@endsection

@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Used Access Codes</h1>
			
			<div class="separator mb-5"></div>
		</div>	
	</div>

    <form action="{{ route('used.list.search')}}" method ='GET' class='profile form-horizontal' enctype='multipart/form-data'>
	<div class="row">
		<div class="col-md-12 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="form-group col-lg-8">
									<input type="search" name="search_used" id="title" class="form-control" placeholder="Search By Access Code">
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

    {{-- Used Access Codes Table View --}}

    <div class="card">
		<div class="card-body">
			<div class="table-responsive"  id="tag_container">
				<div class="col-lg-12">
					<div class="box box-primary">
						<div class="box-body">
							@include('flash-message')
                            <div id="used_table">
                            <table class="table table-hover">
                                <tr class="thead bg-primary">
                                   <th>Used Access Codes</th>
                                   <th>Used By</th>
                                   <th>Used date</th>
                                   <th width="250px">Action</th>
                                    </tr> 
                                    @if(!count($usedcodes) < 1)
                                    @foreach ($usedcodes as $usedcode)
                                        <tr class="thead-light">
                                          <td>{{ $usedcode->number }}</td>
                                          <td>Used by user : ({{ $usedcode->first_name}} {{ $usedcode->last_name}})</td>
                                          <td>Used date : ({{ date('Y-m-d',$usedcode->used_date)}})</td>	
                                              <td>
                                            <form action="{{ route('manual.used.list') }}" method ='POST' class='profile form-horizontal' enctype='multipart/form-data'>
                                                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                                <div class="form-group col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-8 row col-xs-12">
                                                            <div class="col-md-12 col-xs-12 field mb-4">
                                                    <div class="form-group col-md-12">
                                                        <div class="sign-up-btn ">
                                    
                                                          @csrf @method('Delete')
                                                          <a title="Delete Used Access Code"  data-id="{{$usedcode->serial_id}}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Used Code?"  data-left_button_name ="Yes" data-left_button_id ="used_access_code_del" data-left_button_cls="btn-primary" class="open_confirmBox action used_access_code_delete"  href="javascript:void(0)" data-game_id="{{$usedcode->serial_id}}">
                                                          <i class="glyph-icon simple-icon-trash"></i>
                                                         </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
<script src="{{ asset('js/module/used_access_code.js')}}"></script>
@stop
@endsection
