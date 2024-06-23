<table class="table table-hover mb-0">
	<thead class="bg-primary">
		<tr>
		<th scope="col">ID</th>
		<th scope="col">Image</th>
		<th scope="col">Title</th>
		<th scope="col">Game</th>
		<th scope="col">Guide Type</th>
		<th scope="col">Status</th>
		<th scope="col">Action</th>
		</tr>
	</thead>
	<tbody>
	 @if(is_object($guides) && !empty($guides) && $guides->count())
		 @php $sno = 1;$sno_new = 0  @endphp
		
	  @foreach($guides as $key => $guide)
		@include('admin.game_guides.gameSingleRow')
		@php $sno++ @endphp
	 @endforeach
 @else
<tr><td colspan="7" class="error" style="text-align:center">No Data Found.</td></tr>
 @endif	
		
	</tbody>
</table> 
	<!------------ Pagination -------------->
	@if(is_object($guides) && !empty($guides) && $guides->count()) 
	 	{!! $guides->render() !!}  
	@endif	