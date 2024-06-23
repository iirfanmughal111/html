<div class="card">
	<div class="card-body">
		<div class="table-responsive"  id="tag_container">
			<div class="col-lg-12">
				<div class="box box-primary">
					<div class="box-body">
						@include('flash-message')
<table class="table table-bordered table-hover">
	<tr class="thead-dark">
		<th>Access Codes</th>
		<th width="200px">Action</th>
	</tr>
	
	@foreach ($usedcodes as $usedcode)
	<tr class="thead-light">
		<td>{{ $usedcode->number }}</td>	
		<td>
			<form action="" method="POST">
				
				<a title="Delete Used Access Code"  data-id="{{$usedcode->serial_id}}"
					data-confirm_type="complete"
					data-confirm_message ="Are you sure you want to delete the Used Access Code?"
					data-left_button_name ="Yes"
					data-left_button_id ="used_access_code_del"
					data-left_button_cls="btn-primary"
					class="open_confirmBox action used_access_code_delete"
					href="javascript:void(0)"
					data-access_code_id="{{$usedcode->serial_id}}">
					
					<i class="glyph-icon simple-icon-trash"></i>
				</a>
			</form>
		</td>
	</tr>
	@endforeach
	
</table>

</div>
</div>
</div>
</div>
</div>
</div>
