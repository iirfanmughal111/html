@extends('admin.layouts.admin')
@section('headtitle')
| Access Codes
@endsection

@section('content')
	<div class="row">
		<div class="col-12">
			<h1>Access Codes</h1>
			
				<span class="fl_right balance"><a id="create_user" class="btn btn-primary" href="{{'/admin/access-codes/list'}}">Used List</a></span>
				<span class="fl_right balance"><a id="create_user" class="btn btn-primary" href="{{'/admin/access-codes/auto'}}">Auto</a></span>
				<span class="fl_right balance"><a id="create_user" class="btn btn-primary" href="{{'/admin/access-codes/manual'}}">Manual</a></span>
			
			<div class="separator mb-5"></div>
		</div>	
	</div>

@endsection