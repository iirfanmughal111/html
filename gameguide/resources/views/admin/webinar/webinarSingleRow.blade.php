<tr data-webinar-id="{{ $webinar->id }}" class="webinar_row_{{$webinar->id}}">
    <td id="sno_{{$webinar->id}}"> {{++$key}} </td>
    <td id="title_{{$webinar->id}}">{{$webinar->title}}</td>

    <td id="image_{{$webinar->id}}">
        <img src="{{url('uploads/webinar/logos/'.$webinar->logo_image)}}" class="logo_image" style="width:30px;"
            alt="no - image">
    </td>
    <td id="image_{{$webinar->id}}">
        <img src="{{url('uploads/webinar/featured_images/'.$webinar->featuredImg_image)}}" class="logo_image"
            style="width:30px;" alt="no - image">
    </td>
    <td id="start_date_{{$webinar->id}}">{{ Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toDateString() }}

    <td id="type_{{$webinar->id}}">{{ Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toTimeString() }}</td>
    <td id="type_{{$webinar->id}}">{{Carbon\Carbon::createFromTimestamp($webinar->end_datetime)->toDateString()}}</td>
    <td id="type_{{$webinar->id}}">{{Carbon\Carbon::createFromTimestamp($webinar->end_datetime)->toTimeString()}}</td>
    <!-- <td id="status_{{$webinar->id}}">{{ $webinar->status}}</td> -->
    <!--					<td id="status_{{$webinar->id}}">
		f
@php  $selected=''; @endphp
		@if($webinar->status==1)
		@php	$selected = 'checked=checked'@endphp
		@endif	
		<div class="custom-switch  custom-switch-primary custom-switch-small">
			<input class="custom-switch-input switch_status" id="switch{{ $webinar->id }}" type="checkbox" data-webinar_id="{{ $webinar->id }}" {{$selected}}>
			   <label class="custom-switch-btn" for="switch{{ $webinar->id }}"></label>

		  </div>
	</td> -->





    <td id="action_{{$webinar->id}}">

        @if(check_role_access('webinar_edit'))
        <a class="action editWebinar" href="{{'/admin/webinar/edit/'}}{{$webinar->id}}"
            data-webinarId="{{ $webinar->id }}" title="Edit Webinar"><i class="simple-icon-note"></i> </a>
        @endif

        @if(check_role_access('webinar_delete'))
        <a title="Delete Webinar" href="{{url ('admin/webinar/delete_webinar/'.$webinar->id)}}"><i
                class="simple-icon-trash"></i></a>

        <!-- <a title="Delete Webinar"  data-id="{{ $webinar->id }}" class="delete_webinar" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Webinar?"  data-left_button_name ="Yes" data-left_button_id ="delete_webinar" data-left_button_cls="btn-primary" class="open_confirmBox action deleteWebinar"  href="javascript:void(0)" data-webinar_id="{{ $webinar->id }}"><i class="simple-icon-trash"></i></a> -->
        @endif

    </td>
</tr>