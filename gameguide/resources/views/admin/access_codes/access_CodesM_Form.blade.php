
@extends('admin.layouts.admin')
@section('headtitle')
| Create Access Code
@endsection

@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Create Access Code</h1>			
			<div class="separator mb-5"></div>
		</div>	
	</div>

<div class="card">
		<div class="card-body">
			<div class="table-responsive"  id="tag_container">
				<div class="col-lg-12">
					<div class="box box-primary">
						<div class="box-body">
							
                        <form action="{{ route('manual.save') }}" method ='POST' class='profile form-horizontal' enctype='multipart/form-data'>
							<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
							@csrf
							@if ($check==true)

						
							<input type = "hidden" name = "old_id" value ='<?php echo $getupdte[0]->serial_id; ?>'>	
							@endif
							
							<div class="form-group col-md-12">
								@include('flash-message')	
								<label>Access Code</label>
									
										@if ($check==true)
										<textarea class="form-control" name="number"  placeholder="Add Access Codes"><?php echo $getupdte[0]->number; ?></textarea>
										@else
										<textarea class="form-control" name="number"placeholder="Add Access Codes" placeholder="Enter The Access Code"></textarea>
										<span class="error"> {{ $errors->first('number')  }} </span>
										@endif
									
								</div>
                            <div class="form-group col-md-12">
									 <div class="sign-up-btn ">
										 @if ($check)
										 <button name="update" class="btn btn-primary " type="submit">Update</button> 
										 @else
										 <button name="save" class="btn btn-primary" type="submit">Save</button>
										 @endif	  
										 <a href="{{url('admin/access-codes/manual')}}" name="back" class="btn btn-primary" value="Back" type="submit">Back</a>
									</div>
							</div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('additionJs')
<script src="{{ asset('js/module/access_codes.js')}}"></script>
@stop
@endsection