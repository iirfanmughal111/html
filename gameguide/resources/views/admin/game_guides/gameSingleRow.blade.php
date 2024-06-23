<tr data-guide-id="{{ $guide->id }}" class="user_row_{{$guide->id}}" >		
	<td id="sno_{{$guide->id}}">{{(($page_number-1) * 10)+$sno}} 
		<input type="hidden" name="page_number" value="{{$page_number}}" id="page_number_{{$guide->id}}"/>
		<input type="hidden" name="sno" value="{{$sno}}" id="s_number_{{$guide->id}}"/>
	</td>
	<td id="image_{{$guide->id}}">
		@if($guide->image != NULL)
			<img src="{{$guide->image_url}}" class="guide_image" style="width:30px;">
		@endif
	</td>
	<td id="title_{{$guide->id}}">{{$guide->title}}</td>
	<td id="game_{{$guide->id}}">{{$guide->game->title}}</td>
	<td id="type_{{$guide->id}}">{{$guide->guideType->name}}</td>
	
	<td id="status_{{$guide->id}}">
		@php  $selected=''; @endphp
		@if($guide->status==1)
		@php	$selected = 'checked=checked'@endphp
		@endif	
		<div class="custom-switch  custom-switch-primary custom-switch-small">
			<input class="custom-switch-input switch_status" id="switch{{ $guide->id }}" type="checkbox" data-guide_id="{{ $guide->id }}" {{$selected}}>
			   <label class="custom-switch-btn" for="switch{{ $guide->id }}"></label>

		  </div>
	</td>
	<td id="action_{{$guide->id}}">
		
		@if(check_role_access('game_guides_edit'))
			<a class="action editGuide" href="{{'/admin/game-guides/edit/'}}{{$guide->id}}" data-guideId="{{ $guide->id }}" title="Edit Guide"><i class="simple-icon-note"></i> </a> 
		@endif
		
		@if(check_role_access('game_guides_delete'))
			<a title="Delete Guide"  data-id="{{ $guide->id }}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Guide?"  data-left_button_name ="Yes" data-left_button_id ="delete_guide" data-left_button_cls="btn-primary" class="open_confirmBox action deleteGuide"  href="javascript:void(0)" data-guide_id="{{ $guide->id }}"><i class="simple-icon-trash"></i></a>
		@endif	
		
	</td>	
</tr>