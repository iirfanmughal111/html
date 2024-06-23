@extends('admin.layouts.admin')
@section('headtitle')
Access log
@endsection

@section('content')


	
<div class="row">
		<div class="col-12">
			<h1>Access log</h1>
			
				
			
			<div class="separator mb-5"></div>
		</div>	
	</div>

	{{-- search Part  --}}

	<form action="{{ route('accesslog.search') }}" method ='GET' class='profile form-horizontal' enctype='multipart/form-data'>
		<div class="row">
			<div class="col-md-12 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="form-group col-lg-6">
										<input type="search" name="search_number" id="title" class="form-control" placeholder="Search By Access Code">
									</div>
									
									<div class="form-group col-lg-6">
							<select  id="role_id"  class="form-control select2-single"  name="generation_client"  data-width="100%">
										
								<option value="">All clients</option>
								@foreach($clients as $client)
								
								<option value="{{$client}}" {{ ( $client == $selected) ? 'selected' : '' }}>{{$client}}</option>
							
								@endforeach
								
							</select>
						</div>
									
									
									
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-lg-6">
								<button type="submit" name="search" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">Search</button>
								<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
	
								<a href="{{url('admin/access-logs')}}" name="back" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto" value="Back" type="submit">Back</a>
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
		                                    <th>Client</th>
										 <th>Date</th>
										  <th>Status</th>
										 <th>User Id</th>
	                                            </tr>
	                                                @if(!count($records) < 1)
	                                                   @foreach ($records as $record)
	                                                       <tr class="thead-light">
		                                                     <td>{{ $record->access_code }}</td>
									   <td>{{ $record->generation_client }}</td>
									  <td>{{ \Carbon\Carbon::parse($record->generation_date)->format('d/m/Y')}}</td>
									 <td>{{($record->redeemed_status)?'used':'not used'}}</td>
									 <td>{{$record->redeemed_user_profile}}</td>
		                                                         
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
