<table class="table table-hover mb-0">
	<thead class="bg-primary">
		<tr>
		<th scope="col">ID</th>
		<th scope="col">Title</th>
		<th scope="col">logo</th>
		<th scope="col">Featured Image</th>
		<th scope="col">Start Date</th>
		<th scope="col">Start Time</th>
		<th scope="col">End Date</th>
		<th scope="col">End Time</th>
		<!-- <th scope="col">Status</th> -->
		<th scope="col">Action</th>
		</tr>
	</thead>
	<tbody>
	 @if(is_object($webinars ) && !empty($webinars) && $webinars->count())
		 @php $sno = 1;$sno_new = 0  @endphp
		
	  @foreach($webinars as $key => $webinar)
		@include('admin.webinar.webinarSingleRow')
		@php $sno++ @endphp
	 @endforeach
 @else
<tr><td colspan="7" class="error" style="text-align:center">No Data Found.</td></tr>
 @endif	
		
	</tbody>
</table> 
	<!------------ Pagination -------------->
	@if(is_object($webinars) && !empty($webinars) && $webinars->count()) 
	 	{!! $webinars->render() !!}  
	@endif	