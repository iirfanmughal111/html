@extends('frontend.layouts.master')
@section('headtitle')
| Chat
@endsection

@section('content')
	@include('frontend.common.header')

	<div class="wrapper">
    	@include('frontend.partials.account_top')
    	@include('frontend.partials.guide_bar')

	<div class="container pt-5">
        
            <div class="row">


              <table class="table table-hover mb-0">
	<thead class="bg-primary">
		<tr>
		<th scope="col">Name</th>
		<th scope="col">Chat</th>
		</tr>
	</thead>
	<tbody>
	 @if(is_object($users) && !empty($users) && $users->count())
		 @php $sno = 1;$sno_new = 0  @endphp
		
	  @foreach($users as $key => $user)
          
          <tr>
              <td>
                  {{$user->first_name}}  {{$user->last_name}}
              </td>

          
              <td>
		<a class="action" target = "_blank" href="{{url('manage-user')}}/{{$user->id}}"  data-user_id="{{ $user->id }}" title="Manage Customer"><i class="simple-icon-bubbles"></i> 
				{{-- Check if customer unread count greater then 0 --}}
                                
                                Click Chat
<!--				@php
					$unreadCounter = unreadMessageCount($user->id);
				@endphp
				@if(isset($unreadCounter) && $unreadCounter > 0)
					<span class="unreadStatus">{{$unreadCounter}}</span>
				@endif-->
			</a> 
                  </td>

				  <td>



				</td>



        </tr>
	 @endforeach
 @else
<tr><td colspan="7" class="error" style="text-align:center">No Data Found.</td></tr>
 @endif	
		
	</tbody>
</table> 
            </div>

	</div>
	
	@include('frontend.common.footer')
@stop



	  