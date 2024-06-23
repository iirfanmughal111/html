<tr data-game-id="{{ $game->id }}" class="user_row_{{$game->id}}" >		
	<td id="sno_{{$game->id}}">{{(($page_number-1) * 10)+$sno}} 
		<input type="hidden" name="page_number" value="{{$page_number}}" id="page_number_{{$game->id}}"/>
		<input type="hidden" name="sno" value="{{$sno}}" id="s_number_{{$game->id}}"/>
	</td>
	<td id="image_{{$game->id}}">
		@if($game->image != NULL)
			<img src="{{$game->image_url}}" class="game_image" style="width:30px;">
		@endif
	</td>
	<td id="title_{{$game->id}}">
		@if(check_role_access('game_edit')) 
			<a class="action editGame action_title" href="{{'/admin/games/edit/'}}{{$game->id}}" data-gameId="{{ $game->id }}" title="{{$game->title}}">{{$game->title}} </a> 
		@else
			{{$game->title}}
		@endif
	</td>
	
	<td id="title_{{$game->id}}">
		@if($game->position) 
			{{$game->position}} 
		@else
			-
		@endif
	</td>
	
	
	<td id="status_{{$game->id}}">
		@php  $selected=''; @endphp
		@if($game->status==1)
		@php	$selected = 'checked=checked'@endphp
		@endif	
		<div class="custom-switch  custom-switch-primary custom-switch-small">
			<input class="custom-switch-input switch_status" id="switch{{ $game->id }}" type="checkbox" data-game_id="{{ $game->id }}" {{$selected}}>
			   <label class="custom-switch-btn" for="switch{{ $game->id }}"></label>

		  </div>
	</td>
	<td id="action_{{$game->id}}">
		
		@if(check_role_access('game_edit'))
			<a class="action editGame" href="{{'/admin/games/edit/'}}{{$game->id}}" data-gameId="{{ $game->id }}" title="Edit Game"><i class="simple-icon-note"></i> </a> 
		@endif
		
		@if(check_role_access('game_delete'))
			<a title="Delete Game"  data-id="{{ $game->id }}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Game?"  data-left_button_name ="Yes" data-left_button_id ="delete_game" data-left_button_cls="btn-primary" class="open_confirmBox action deleteGame"  href="javascript:void(0)" data-game_id="{{ $game->id }}"><i class="simple-icon-trash"></i></a>
		@endif	
		
	</td>	
</tr>