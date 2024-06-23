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
	
	@foreach ($records as $record)
	<tr class="thead-light">
		<td>{{ $record->number }}</td>	
		<td>
			<form action="" method="POST">
				<a class="glyph-icon simple-icon-note" href="edit/{{$record->serial_id}}"></a>
				
				<a title="Delete Access Code"  data-id="{{$record->serial_id}}"
					data-confirm_type="complete"
					data-confirm_message ="Are you sure you want to delete the Access Code?"
					data-left_button_name ="Yes"
					data-left_button_id ="delete_access"
					data-left_button_cls="btn-primary"
					class="open_confirmBox action access_code_delete"
					href="javascript:void(0)"
					data-access_code_id="{{$record->serial_id}}">
					
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
