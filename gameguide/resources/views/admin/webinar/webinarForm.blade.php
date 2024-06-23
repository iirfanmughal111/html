@extends('admin.layouts.admin')
@section('content')
@section('ckeditor')
<script src="//cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('description', {
    allowedContent: true
});
</script>
@stop
{{-- Check if New webinar or Edit webinar, if $newWebinar set 1 then new webinar else Edit webinar --}}
@php
$newWebinar = 1;
$webinarTitle = 'Add';
$action = 'Add';
$keynoteFound = 0;

@endphp
@if(isset($webinar))
@php
$newWebinar = 2;
$webinarTitle = 'Edit';
$action = 'Update'


@endphp
@endif





@section('headtitle')
| {{$webinarTitle}} Webinar
@endsection


<div class="row">
    <div class="col-12">
        <h1>{{$webinarTitle}} Webinar</h1>
        <div class="separator mb-5"></div>
    </div>
</div>
<!-- Main content -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive" id="tag_container">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-body">
                        @include('flash-message')

                        @if($newWebinar == 2)
                        @if($errors->first('webinar_id'))
                        <span class="error"> {{ $errors->first('webinar_id')  }} </span>
                        @endif
                        @endif

                        <div class="form-group col-md-12">
                            <div class="row">
                                <div class="col-md-8 row col-xs-12">
                                    <div class="col-md-12 col-xs-12 field mb-4">
                                        @if($newWebinar == 1)
                                        <form method="post" enctype="multipart/form-data"
                                            action="{{url ('admin/webinar/create')}}">
                                            @else
                                            <form method="post" enctype="multipart/form-data"
                                                action="{{url ('admin/webinar/update')}}">
                                                @endif
                                                <!-- <form method="post" enctype="multipart/form-data" action="{{url ('admin/webinar/create')}}"> -->
                                                @csrf

                                                @if($newWebinar == 1)
                                                <div class="form-group">
                                                    <label for="webinarTitle">Title</label>
                                                    <input type="text" class="form-control" name="title"
                                                        placeholder="Title" required>
                                                </div>
                                                <div class="form-floating mb-4">
                                                    <label for="floatingTextarea2">Description</label>
                                                    <textarea class="form-control" placeholder="Description"
                                                        name="webinarDescription" style="height: 100px"
                                                        required></textarea>

                                                </div>


                                                <div class="row">
                                                    <div class="col">
                                                        <label for="startDate">Start Date</label>
                                                        <input type="date" min="{{ now()->toDateString('Y-m-d') }}"
                                                            class="form-control" id="start-date" name="startDate"
                                                            required>
                                                    </div>
                                                    <div class="col">
                                                        <label for="startTime">Start Time</label>
                                                        <input type="time" class="form-control" id="start-time"
                                                            name="startTime" required>
                                                    </div>
                                                </div>

                                                <div class="row my-3">
                                                    <div class="col">
                                                        <label for="endDate">End Date</label>
                                                        <input type="date" id="end-date" class="form-control"
                                                            min="{{ now()->toDateString('Y-m-d') }}" name="endDate"
                                                            required>
                                                    </div>
                                                    <div class="col">
                                                        <label for="endTime">End Time</label>
                                                        <input type="time" id="end-time" class="form-control"
                                                            name="endTime" required>
                                                    </div>
                                                </div>

                                                <div class="row my-3">
                                                    <div class="col">
                                                        <label class="mt-2 mb-3" for="fileupload"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="17" viewBox="0 0 20 17">
                                                                <path
                                                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                                                </path>
                                                            </svg> <span>Choose a Webinar Logo</span></label>
                                                        <input class="form-control" type="file" name="logo" required>

                                                    </div>
                                                    <div class="col">
                                                        <label class="mt-2 mb-3" for="fileupload"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="17" viewBox="0 0 20 17">
                                                                <path
                                                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                                                </path>
                                                            </svg> <span>Choose a Featured Image</span></label>
                                                        <input class="form-control" type="file" name="featuredImg"
                                                            required>
                                                    </div>
                                                </div>
                                                <h1>Host Details</h1>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="webinarlink">Webinar Video Id</label>
                                                            <input type="text" class="form-control" name="webinarlink"
                                                                required placeholder="webinarlink">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="webinarkey">Webinar Key</label>
                                                            <input type="text" class="form-control" name="streamkey"
                                                                required placeholder="webinarkey">
                                                        </div>
                                                    </div>
                                                </div>
                                                <label for="hostemail">Chose coach</label>
                                                <div class="form-group">
                                                    <select id="coach_id" class="form-control" name="coach_id" required>


                                                        @foreach ($coaches as $key=>$coach)
                                                        <option value="{{$coach->id}}">{{$coach->full_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-12 col-xs-12 field mb-4">

                                                    <label for="notifications">Notifications </label>

                                                    <div class="notification_fields_wrap">
                                                        <a href="javascript:void(0);"
                                                            class="add_field_notification btn btn-primary mb-4"
                                                            id="notification-btn">Add More Notification</a>
                                                    </div>

                                                    <div class="row new_notification">
                                                        <div class="col-md-8 col-xs-8 field mb-4">
                                                            <div class="row my-3">
                                                                <div class="col">
                                                                    <label for="notificationdate">Notification
                                                                        Date</label>
                                                                    <input type="date"
                                                                        class="form-control notification-date"
                                                                        name="notificationdate[]" required>
                                                                </div>
                                                                <div class="col">
                                                                    <label for="notificationtime">Notification
                                                                        Time</label>
                                                                    <input type="time"
                                                                        class="form-control notification-time"
                                                                        name="notificationtime[]" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>




                                                <div class="clearfix"></div>
                                                <div class="col-md-12 col-xs-12 field mb-4">

                                                    <label for="keynote">Key </label>

                                                    <div class="input_fields_wrap">
                                                        <a href="javascript:void(0);"
                                                            class="add_field_key btn btn-primary mb-4">Add More Key</a>
                                                    </div>

                                                    <div class="row new_keynote">
                                                        <div class="col-md-8 col-xs-8 field mb-4">
                                                            <input type="text" name="webinarkey[]"
                                                                class="key_input form-control">
                                                        </div>
                                                    </div>

                                                </div>

                                    </div>
                                    @else

                                    <div class="form-group">
                                        <label for="webinarTitle">Title</label>
                                        <input type="text" value="{{$webinar->title}}" class="form-control" name="title"
                                            placeholder="Title" required>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <label for="floatingTextarea2">Description</label>
                                        <textarea class="form-control" placeholder="Description"
                                            name="webinarDescription" style="height: 100px"
                                            required>{{$webinar->description}}</textarea>

                                    </div>


                                    <div class="row">
                                        <div class="col">
                                            <label for="startDate">Start Date</label>

                                            <!-- <?php
                                            //  $t = getDateTime($webinar->start_datetime);
                                            ?> -->


                                            <input type="date"
                                                value="{{Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toDateString()}}"
                                                class="form-control" id="update-start-date" name="startDate" required>
                                        </div>
                                        <div class="col">
                                            <label for="startTime">Start Time</label>
                                            <input type="time"
                                                value="{{Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toTimeString()}}"
                                                class="form-control" id="update-start-time" name="startTime" required>
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col">
                                            <label for="endDate">End Date</label>
                                            <input type="date"
                                                value="{{Carbon\Carbon::createFromTimestamp($webinar->end_datetime)->toDateString()}}"
                                                class="form-control" id="update-end-date" name="endDate" required>
                                        </div>
                                        <div class="col">
                                            <label for="endTime">End Time</label>
                                            <input type="time"
                                                value="{{Carbon\Carbon::createFromTimestamp($webinar->end_datetime)->toTimeString()}}"
                                                class="form-control" id="update-end-time" name="endTime" required>
                                        </div>
                                    </div>

                                    <div class="row my-3">
                                        <div class="col">
                                            <label class="mt-2 mb-3" for="fileupload"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="20" height="17"
                                                    viewBox="0 0 20 17">
                                                    <path
                                                        d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                                    </path>
                                                </svg> <span>Choose a Webinar Logo</span></label>
                                            <input class="form-control" type="file" name="logo">

                                        </div>
                                        <div class="col">
                                            <label class="mt-2 mb-3" for="fileupload"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="20" height="17"
                                                    viewBox="0 0 20 17">
                                                    <path
                                                        d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                                    </path>
                                                </svg> <span>Choose a Featured Image</span></label>
                                            <input class="form-control" type="file" name="featuredImg">
                                        </div>
                                    </div>

                                    <h1>Host Details</h1>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="webinarlink">Webinar Video Id</label>
                                                <input type="text" class="form-control" name="webinarlink"
                                                    value="{{$webinar->webinar_link}}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="streamkey">Stream Key</label>
                                                <input type="text" class="form-control" name="streamkey"
                                                    value="{{$webinar->streamKey}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <select id="coach_id" class="form-control" name="coach_id" required>


                                            @foreach ($coaches as $key=>$coach)

                                            @if($coach->id == $webinar->coach_user_id)
                                            <option value="{{$coach->id}}" selected>{{$coach->full_name}} </option>
                                            @endif
                                            <option value="{{$coach->id}}">{{$coach->full_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 col-xs-12 field mb-4">

                                        <label for="notifications">Notifications </label>

                                        <div class="notification_fields_wrap">
                                            <a href="javascript:void(0);"
                                                class="add_field_notification btn btn-primary mb-4"
                                                id="notification-btn">Add More Notification</a>

                                            @foreach($notifications as $notif)
                                            <div class="row new_notification">
                                                <div class="col-md-8 col-xs-8 field mb-4">
                                                    <div class="row my-3">
                                                        <div class="col">
                                                            <label for="notificationdate">Notification
                                                                Date</label>
                                                            <input type="date" class="form-control notification-date"
                                                                name="notificationdate[]" required
                                                                value="{{Carbon\Carbon::createFromTimestamp($notif->notification_datetime)->toDateString()}}">
                                                        </div>
                                                        <div class="col">
                                                            <label for="notificationtime">Notification
                                                                Time</label>
                                                            <input type="time" class="form-control notification-time"
                                                                name="notificationtime[]" required
                                                                value="{{Carbon\Carbon::createFromTimestamp($notif->notification_datetime)->toTimeString()}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-4 field mb-4"><a href="#"
                                                        class="remove_notification mt-5">Remove</a></div>
                                            </div>
                                            @endforeach

                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="col-md-12 col-xs-12 field mb-4">

                                            {{ Form::label('Key') }}
                                            <div class="input_fields_wrap">
                                                <a href="javascript:void(0);"
                                                    class="add_field_key btn btn-primary mb-4">Add
                                                    More Key</a>

                                                @foreach($keys as $key)
                                                <div class="row new_keynote">
                                                    <div class="col-md-8 col-xs-8 field mb-4">
                                                        <input type="text" name="webinarkey[]" value="{{$key->content}}"
                                                            class="key_input form-control">
                                                    </div>
                                                    <div class="col-md-4 col-xs-4 field mb-4"><a href="#"
                                                            class="remove_field">Remove</a></div>
                                                </div>
                                                @endforeach

                                            </div>

                                        </div>

                                        @endif
                                        <div class="clearfix"></div>


                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="sign-up-btn ">
                                    @if($newWebinar == 2)
                                    <input type="hidden" value="{{$webinar->id}}" name="webinar_id" id="webinar_id">
                                    @endif
                                    <input name="submit" class="loginmodal-submit btn btn-primary" id="webinar_update"
                                        value="{{$action}}" type="submit">
                                    <a href="{{url('admin/webinar')}}" name="back"
                                        class="loginmodal-submit btn btn-primary" id="profile_back" value="Back"
                                        type="submit">Back</a>
                                </div>
                            </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stop

    @section('additionJs')
    <script src="{{ url('js/module/webinar.js')}}"></script>
    @stop