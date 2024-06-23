@extends('admin.layouts.admin')
@section('headtitle')
|Auto Access Codes
@endsection

@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Auto Access Codes</h1>
			
			<div class="separator mb-5"></div>
		</div>	
	</div>

    <form action="{{ route('used.auto.search')}}" method ='GET' class='profile form-horizontal' enctype='multipart/form-data'>
	<div class="row">
		<div class="col-md-12 mb-4">
			<div class="card h-100">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="form-group col-lg-8">
									<input type="search" name="search_auto" id="title" class="form-control" placeholder="Search By Access Code">
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
                                   <th>Auto Access Codes</th>
                                   <th>Used By</th>
								   <th>Used Date</th>
                                    </tr> 
									@if(!count($auto_records) < 1)
                                    @foreach ($auto_records as $auto_record)
                                        <tr class="thead-light">
                                          <td>{{ $auto_record->number }}</td>
										  <td>Used by user : ({{ $auto_record->first_name}} {{ $auto_record->last_name}})</td>
										  <td>Used date : ({{ date('Y-m-d',$auto_record->used_date)}})</td>
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
@endsection
