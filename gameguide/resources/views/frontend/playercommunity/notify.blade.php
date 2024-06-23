@foreach ($userlist as $notification)

@if($notification['status']=='0')
<div class="row mt-1 notification">

    <div class="col-xs-12 col-sm-12 col-md-4 left-content">


        <img id="user-image" src="{{$notification['imagePath']}}" width="50px" />

    </div>



    <div class="col-xs-12 col-sm-12 col-md-8 right-content">
        <div class="user-notify">
            <p>{{$notification['first_name']}} send reqeust</p>

            <ul class="button-list">
                <li> <a href="{{url('accept-frnd')}}/{{$notification['user_id']}}" class="btn btn-default"> Confirm</a>
                </li>

                <li> <a href="{{url('reject-frnd')}}/{{$notification['user_id']}}" class="btn btn-default"> Delete</a>
                </li>
            </ul>

        </div>
    </div>

</div>
@endif
@if($notification['status']=='1')
<div class="row mt-1 notification">

    <div class="col-xs-12 col-sm-12 col-md-4 left-content">


        <img id="user-image" src="{{$notification['imagePath']}}" width="50px" />

    </div>



    <div class="col-xs-12 col-sm-12 col-md-8 right-content">
        <div class="user-notify">
            <p>{{$notification['first_name']}} accept request</p>

            <ul class="button-list">
                <li> <a href="{{url('manage-user')}}/{{$notification['user_id']}}" class="btn btn-default "> Chat</a>
                </li>
                <li> <a href="{{url('un-frnd')}}/{{$notification['user_id']}}" class="btn btn-default "> Unfriend</a>
                </li>

            </ul>

        </div>
    </div>

</div>
@endif

@if($notification['status']=='2')
<div class="row mt-1 notification">

    <div class="col-xs-12 col-sm-12 col-md-4 left-content">


        <img id="user-image" src="{{$notification['imagePath']}}" width="50px" />

    </div>



    <div class="col-xs-12 col-sm-12 col-md-8 right-content">
        <div class="user-notify">
            <p>{{$notification['first_name']}} reject request</p>

            <ul class="button-list">

                <li> <a href="{{url('add-frnd')}}/{{$notification['user_id']}}?notify_id={{$notification['id']}}"
                        class="btn btn-default friend">Add Friend</a></li>

            </ul>

        </div>
    </div>
</div>
@endif

@if($notification['status']==4)
<div class="row mt-1 notification">

    <div class="col-xs-12 col-sm-12">
        <a href="{{url('webinars')}}/{{$notification['webinar_id']}}" class="friend">{{$notification['title']}}</a>
    </div>

    <div class="col-xs-12 col-sm-12">
        <div class="user-notify">
            <p style="font-size:11px;">{{$notification['message']}}</p>
        </div>
    </div>
</div>
@endif



@endforeach