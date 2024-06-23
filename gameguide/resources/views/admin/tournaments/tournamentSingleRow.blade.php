<tr data-tournament-id="{{ $tournament->id }}" class="user_row_{{$tournament->id}}" >		
	<td id="sno_{{$tournament->id}}">{{(($page_number-1) * 10)+$sno}} 
		<input type="hidden" name="page_number" value="{{$page_number}}" id="page_number_{{$tournament->id}}"/>
		<input type="hidden" name="sno" value="{{$sno}}" id="s_number_{{$tournament->id}}"/>
	</td>
	<td id="image_{{$tournament->id}}">
		@if($tournament->image != NULL)
			<img src="{{$tournament->image_url}}" class="tournament_image" style="width:30px;">
		@endif
	</td>
	<td id="title_{{$tournament->id}}">
		@if(check_role_access('tournament_edit')) 
			<a class="action editTournament action_title" href="{{'/admin/tournaments/edit/'}}{{$tournament->id}}" data-tournamentId="{{ $tournament->id }}" title="{{$tournament->title}}">{{$tournament->title}} </a> 
		@else
			{{$tournament->title}}
		@endif
	</td>
	
	{{--<td id="status_{{$tournament->id}}">
		@php  $selected=''; @endphp
		@if($tournament->status==1)
		@php	$selected = 'checked=checked'@endphp
		@endif	
		<div class="custom-switch  custom-switch-primary custom-switch-small">
			<input class="custom-switch-input switch_status" id="switch{{ $tournament->id }}" type="checkbox" data-tournament_id="{{ $tournament->id }}" {{$selected}}>
			   <label class="custom-switch-btn" for="switch{{ $tournament->id }}"></label>

		  </div>
	</td>--}}
	<td id="action_{{$tournament->id}}">
		
		@if(check_role_access('tournament_edit'))
			<a class="action editTournament" href="{{'/admin/tournaments/edit/'}}{{$tournament->id}}" data-tournamentId="{{ $tournament->id }}" title="Edit Tournament"><i class="simple-icon-note"></i> </a> 
		@endif
		
		@if(check_role_access('tournament_delete'))
			<a title="Delete Tournament"  data-id="{{ $tournament->id }}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Tournament?"  data-left_button_name ="Yes" data-left_button_id ="delete_tournament" data-left_button_cls="btn-primary" class="open_confirmBox action deleteTournament"  href="javascript:void(0)" data-tournament_id="{{ $tournament->id }}"><i class="simple-icon-trash"></i></a>
		@endif	
		
	</td>	
</tr>