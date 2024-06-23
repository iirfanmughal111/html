@extends('frontend.layouts.master')
@section('headtitle')
| Webinar
@endsection
@section('content')
@include('frontend.common.header')
<style>
.webinar-hero {
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    padding: 55px 25px 55px 25px;

    text-align: center;
}

.webinar-hero h2 {
    color: #fff;
    font-size: 20px;
    font-weight: 400;
    margin-top: 0;
    text-transform: uppercase;
}

.webinar_timmer {
    display: flex;
    justify-content: center;
    text-align: center;
}

.webinar-logo-img {
    max-height: 110px;
    max-width: 110px;
    min-height: 110px;
    min-width: 110px;

}

#logos-scroll {
    flex-wrap: nowrap;
    overflow-x: hidden;
    margin-top: -50px;
}

#prescrollbtn,
#nextscrollbtn {
    margin-bottom: -8%;
    z-index: 5;
    background-color: blue;
    max-height: 26px;
    color: white;
    border-radius: 50%;
    border: none;
}

#prescrollbtn:focus,
#nextscrollbtn:focus {
    outline: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;

}

.timmerText {
    line-height: 50px;
    font-size: x-large;
}

.dateTimeText {
    font-size: large;
}

.sidebar__title {

    font-size: 15px;
    font-weight: 500;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.sidebarPastWebinars ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.btn-danger {
    background-color: #dc3545 !important;
    margin-left: auto;
    margin-right: auto;

}

.sidebarPastWebinars ul li .icon {
    background: #ccc;
    border-radius: 50%;
    display: inline-block;
    height: 28px;
    margin-right: 10px;
    overflow: hidden;
    width: 28px;
}

.sidebarPastWebinars ul li .icon img {
    height: 100%;
    object-fit: cover;
    width: 100%;
}

.sidebarPastWebinars ul li .iconText {
    /* color: #adadad; */
    color: #2c2828;
    font-size: 15px;
    font-weight: 300;
}

.video_thumbnail {
    display: flex;
    border-radius: 5px;
    background-color: #000000ba;
    border: 10px solid silver;
    background-repeat: no-repeat;
    justify-content: center;
    align-items: center;
    background-size: cover;
    padding: 100px 80px 100px 80px;
}

.thumbnail_play_icon {
    font-size: large;

}

.bg-darkTransparent {
    background-color: #343a40a6;
}

.my-btn {
    background-color: #ffffff;
    border: 1px solid #8B53FF;
    color: #8B53FF;
}

.my-btn:hover {
    background-color: #8B53FF;
    color: #ffffff;


}
</style>
<div class="wrapper">

    @include('frontend.partials.account_top')
    @php
    $name =
    'https://ui-avatars.com/api/?background='.random_dark_color().'&color='.random_light_color().'&size=128&rounded=true&bold=true&name='.Auth()->user()->first_name."+".Auth()->user()->last_name;
    @endphp
    <img src="{{$name}}">
    <div class="main-container" id="app">

        <div class="container-fluid">

            <div class="row">
                <div class="col-12" style="padding:0px;">@include('frontend.partials.guide_bar')</div>

                <div class="col-lg-2 col-md-3 d-md-block d-none sidebarPastWebinars" style="background-color:#F5F5F5;">
                    <h6 class="ml-1 sidebar__title">Past</h6>
                    <ul class=" nav-pills">

                        @if($pastWebinars->count())

                        @foreach($pastWebinars as $key=>$p_webinar)
                        <li class="ml-1 nav-item"><a id="webinar-{{$p_webinar->id}}-tab" class="nav-link"
                                href="{{url('/webinars/'.$p_webinar->id)}}"><span><img
                                        src="{{url('uploads/webinar/logos/'.$p_webinar->logo_image)}}" class="mr-2 icon"
                                        alt="no logo img in databses"></span><span
                                    class="iconText">{{$p_webinar->title}}</span></a></li>
                        @endforeach
                    </ul>
                    <a href="{{url('webinars/past')}}">See more</a>
                    @else
                    <h6 class="mt-5">Not available</h6>
                    @endif

                </div>
                <div class="col-lg-10 col-md-9">

                    <div class="row mt-2">

                        @if(Auth()->user()->role_id==3 && !$isCoachPage)
                        <div class="col-sm-6 mt-3">

                            <h5>Upcomming</h5>

                        </div>
                        <div class="col-sm-6 mb-3 d-flex justify-content-end">

                            <a href="{{url('webinars/coach-webinars/'.Auth()->user()->id)}}"
                                class="my-btn btn nav-link">My Webinars</a>

                        </div>
                        @elseif(Auth()->user()->role_id==3 && $isCoachPage)
                        <div class="col-12 mb-3">

                            <h5>My Webinars</h5>
                        </div>
                        @elseif(Auth()->user()->role_id!=3 && !$isCoachPage)
                        <div class="col-12 mb-3">

                            <h5>Upcomming</h5>
                        </div>
                        @endif

                    </div>

                    <div class="row mt-5">
                        <div class="col-12 mb-5 mt-2" id="LogosWrapper">
                            <div class="col-12 d-flex justify-content-between" id="LogosButtons">
                                <button id="prescrollbtn" class=" " type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                        <path
                                            d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                                    </svg>
                                </button>
                                <button id="nextscrollbtn" class="" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" fill="currentColor"
                                        class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                        <path
                                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                                    </svg>
                                </button>
                            </div>

                            <ul class="nav nav-pills justify-content-center" id="logos-scroll">

                                @if($UpcommingWebinars->count())

                                @foreach($UpcommingWebinars as $key=>$webinar)
                                <li class="nav-item">
                                    <a class="nav-link" onclick="removeActiveClaass();"
                                        id="webinar-{{$webinar->id}}-tab" href="#webinar-{{$webinar->id}}"
                                        data-bs-toggle="tab">
                                        <img src="{{url('uploads/webinar/logos/'.$webinar->logo_image)}}"
                                            class="webinar-logo-img mx-2 rounded"
                                            onload="timmerCounter({{$webinar->id}},{{$webinar->start_datetime}});"
                                            alt="no img">
                                    </a>
                                </li>
                                @endforeach
                                @else
                                <h6 class="mt-5" style="font-size: small;">No Upcomming</h6>
                                @endif
                            </ul>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-12">
                            <div class="tab-content">
                                @if(!empty($selectedWebinar))
                                <div class="tab-pane active fade show" id="webinar-{{$selectedWebinar->id}}">
                                    <div class="row mb-2">
                                        @if(Session::has("success-$selectedWebinar->id"))

                                        <div class="col-12">
                                            <div class="alert alert-danger alert-block">
                                                {{ Session::get("success-$selectedWebinar->id") }}
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-sm-5 d-flex justify-content-center">

                                            <h6 class="text-center dateTimeText">Start : <span
                                                    class="text-primary pr-1 ml-2">

                                                    {{ Carbon\Carbon::createFromTimestamp($selectedWebinar->start_datetime)->toDateTimeString() }}
                                                </span>
                                            </h6>
                                        </div>
                                        <div class="col-sm-5 d-flex justify-content-center">

                                            <h6 class="text-center dateTimeText">End : <span class="text-primary  ml-2">
                                                    {{ Carbon\Carbon::createFromTimestamp($selectedWebinar->end_datetime)->toDateTimeString() }}


                                                </span>
                                            </h6>
                                        </div>

                                        @if(Auth()->user()->id==$selectedWebinar->coach_user_id)
                                        <div class="col-sm-2  d-flex justify-content-end">

                                            <button type="button" class="btn my-btn" data-toggle="modal"
                                                data-target="#WebinarDetailModal"
                                                data-key="{{$selectedWebinar->keypoints}}"
                                                data-webinar="{{$selectedWebinar}}">Quick View</button>

                                        </div>
                                        @endif


                                    </div>
                                    <div class="row">
                                        <div class="col-12 webinar-hero"
                                            style="background-image:url({{url('uploads/webinar/featured_images/'.$selectedWebinar->featuredImg_image)}}">
                                            <h2 class="mb-3">
                                                <span
                                                    class="timmerText p-2 rounded bg-darkTransparent">{{$selectedWebinar->title}}</span>
                                            </h2>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="selectedWebinar-{{$selectedWebinar->id}}"
                                                        class="webinar_timer mt-4 mb-5 ">


                                                        <strong>
                                                            <span
                                                                class="bg-white  timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="days"></span>
                                                            <span
                                                                class="bg-white  timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="hours"></span>
                                                            <span
                                                                class="bg-white  timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="minutes"></span>
                                                            <span
                                                                class="bg-white  timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="seconds"></span>
                                                        </strong>



                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                    <div class="row my-5">

                                        <div class="col-md-6">


                                            @if(isRegistered($selectedWebinar->id) && $selectedWebinar->start_datetime
                                            >= Carbon\Carbon::now()->timestamp)


                                            <button type="button" class="btn btn-danger rounded-pill btn-sm"
                                                data-toggle="modal" data-target="#registrationCancelModal"
                                                data-id="{{$selectedWebinar->id}}"
                                                data-whatever="{{$selectedWebinar->title}}">Cancel
                                                registeration</button>


                                            @elseif(!isRegistered($selectedWebinar->id) &&
                                            $selectedWebinar->start_datetime >= Carbon\Carbon::now()->timestamp)
                                            <button type="button" class="btn  btn-primary rounded-pill btn-sm"
                                                style="min-width:" data-toggle="modal" data-target="#registrationModal"
                                                data-id="{{$selectedWebinar->id}}"
                                                data-whatever="{{$selectedWebinar->title}}">Click to register</button>

                                            @endif


                                            <div class="row mt-4">
                                                <div class="col-12" style="max-height:300px; overflow-y:auto;">
                                                    @if ($selectedWebinar->registeredUers->count()!=0)
                                                    <table class="table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">Users</th>
                                                                <th scope="col">Date</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="webinarUsersBody-{{$selectedWebinar->id}}">

                                                            @foreach($selectedWebinar->registeredUers as $regWeb)
                                                            <tr>
                                                                <td><a
                                                                        href="{{url ('webinars/about-user/'.$regWeb->user_id)}}">{{$regWeb->user_full_name}}</a>
                                                                </td>
                                                                <td>{{$regWeb->created_at}}</td>

                                                            </tr>
                                                            @endforeach


                                                        </tbody>
                                                    </table>
                                                    @else
                                                    <div>
                                                        <p class="text-center">No users registered yet for this webinar
                                                        </p>
                                                    </div>

                                                    @endif
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-6 ">
                                            <div class="video_thumbnail">


                                                @if($selectedWebinar->start_datetime <= Carbon\Carbon::now()->timestamp)
                                                    <a href="{{url('webinars/play/'.$selectedWebinar->id)}}"
                                                        class="bg-white rounded-circle p-3"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="41" height="41"
                                                            fill="currentColor" class="bi bi-play-fill "
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                                                        </svg></a>
                                                    @else
                                                    <h6 class="p-3 bg-white rounded"> The webinar will begin soon.</h6>

                                                    @endif


                                            </div>
                                            <br><br>
                                            <div class="row">
                                                <div
                                                    class="col-12 d-flex justify-content-center flex-column align-items-center">

                                                    <h6>Our Live Host</h6>

                                                    @if(file_exists(public_path('uploads/users/'.$selectedWebinar->CoachDetial->id.'/'.$selectedWebinar->CoachDetial->profile_photo))
                                                    && isset($selectedWebinar->CoachDetial->profile_photo))
                                                    <img src="{{url('uploads/users/'.$selectedWebinar->CoachDetial->id.'/'.$selectedWebinar->CoachDetial->profile_photo)}}"
                                                        class="webinar-logo-img rounded" alt="no host img">
                                                    @endif
                                                    <p class="mt-3">{{$selectedWebinar->CoachDetial->full_name}}
                                                    </p>
                                                </div>
                                                <!--  <div class="col-6 d-flex align-items-center column-flex" >
                                                    <p>{{$selectedWebinar->CoachDetial->full_name}} 
                                                     </p> 
                                                    
                                                </div>-->
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                @else
                                @if(Session::has("success-emptySelectedWebinar"))

                                <div class="col-12">
                                    <div class="alert alert-danger alert-block">
                                        {{ Session::get("success-emptySelectedWebinar") }}
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                    </div>
                                </div>
                                @endif
                                <p class="text-center">No webinars</p>
                                @endif

                                @if($UpcommingWebinars->count())
                                @foreach($UpcommingWebinars as $key=>$webinar)
                                <div class="tab-pane mt-3 fade" id="webinar-{{$webinar->id}}" role="tabpanel"
                                    aria-labelledby="webinar-{{$webinar->id}}-tab">
                                    <div class="row mb-2">
                                        @if(Session::has("success-$webinar->id"))

                                        <div class="col-12">
                                            <div class="alert alert-danger alert-danger">
                                                {{ Session::get("success-$webinar->id") }}
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-sm-5 d-flex justify-content-center">
                                            <h6 class="text-center dateTimeText ">Start : <span
                                                    class="text-primary pr-1 ml-2">
                                                    {{ Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toDateTimeString() }}

                                                </span></h6>
                                        </div>
                                        <div class="col-sm-5 d-flex justify-content-center">

                                            <h6 class="text-center dateTimeText pl-1">End : <span
                                                    class="text-primary  ml-2">
                                                    {{ Carbon\Carbon::createFromTimestamp($webinar->end_datetime)->toDateTimeString() }}

                                                </span></h6>
                                        </div>
                                        @if(Auth()->user()->id==$webinar->coach_user_id && Auth()->user()->role_id==3 &&
                                        $isCoachPage)
                                        <div class="col-sm-2  d-flex justify-content-end">

                                            <button type="button" class="btn my-btn" data-toggle="modal"
                                                data-target="#WebinarDetailModal" data-key="{{$webinar->keypoints}}"
                                                data-webinar="{{$webinar}}">Quick View</button>

                                        </div>
                                        @endif

                                    </div>
                                    <div class="row">
                                        <div class="col-12 webinar-hero"
                                            style="background-image:url({{url('uploads/webinar/featured_images/'.$webinar->featuredImg_image)}}">
                                            <h2 class="mb-3">
                                                <span
                                                    class="timmerText p-2 rounded bg-darkTransparent">{{$webinar->title}}</span>
                                            </h2>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="webinar-{{$webinar->id}}" class="webinar_timer my-2">


                                                        <strong>
                                                            <span
                                                                class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="days-{{$webinar->id}}"></span>
                                                            <span
                                                                class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="hours-{{$webinar->id}}"></span>
                                                            <span
                                                                class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="minutes-{{$webinar->id}}"></span>
                                                            <span
                                                                class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                                id="seconds-{{$webinar->id}}"></span>
                                                        </strong>
                                                        <!-- @if($webinar->start_datetime <=  Carbon\Carbon::now()->timestamp)
                                                <a href="{{url('webinars/play/'.$webinar->id)}}" id="joinBtn-{{$webinar->id}}" class="btn btn-primary">Click to join</a>
                                                @endif -->




                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                    <div class="row my-5">

                                        <div class="col-md-6">
                                            @if(isRegistered($webinar->id) && $webinar->start_datetime >=
                                            Carbon\Carbon::now()->timestamp)



                                            <button type="button" class="btn btn-danger rounded-pill"
                                                style="background-color:#dc3545 !important;" data-toggle="modal"
                                                data-target="#registrationCancelModal" data-id="{{$webinar->id}}"
                                                data-whatever="{{$webinar->title}}">Cancel registeration</button>


                                            @elseif(!isRegistered($webinar->id) && $webinar->start_datetime >=
                                            Carbon\Carbon::now()->timestamp)
                                            <button type="button" class="btn  btn-primary rounded-pill btn-sm"
                                                data-toggle="modal" data-target="#registrationModal"
                                                data-id="{{$webinar->id}}" data-whatever="{{$webinar->title}}">Click to
                                                register</button>
                                            @endif

                                            <div class="row mt-4">
                                                <div class="col-12" style="max-height:300px; overflow-y:auto;">
                                                    @if ($webinar->registeredUers->count()!=0)

                                                    <table class="table" id="webinarUserTable-{{$webinar->id}}">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">Users</th>
                                                                <th scope="col">Date</th>
                                                            </tr>
                                                        </thead>
                                                        @foreach($webinar->registeredUers as $regWeb)
                                                        <tr>
                                                            <td><a
                                                                    href="{{url ('webinars/about-user/'.$regWeb->user_id)}}">{{$regWeb->user_full_name}}</a>
                                                            </td>
                                                            <td>{{$regWeb->created_at}}</td>

                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                    @else
                                                    <p class="text-center d-none" id="emptyUsersList-{{$webinar->id}}">
                                                        No users registered yet for this webinar</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="video_thumbnail">


                                                @if($webinar->start_datetime <= Carbon\Carbon::now()->timestamp)
                                                    <a href="{{url('webinars/play/'.$webinar->id)}}"
                                                        class="bg-white rounded-circle p-3"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="41" height="41"
                                                            fill="currentColor" class="bi bi-play-fill "
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                                                        </svg></a>
                                                    @else
                                                    <h6 class="p-3 bg-white rounded"> The webinar will begin soon.</h6>

                                                    @endif
                                            </div>
                                            <div class="row my-4">
                                                <div
                                                    class="col-12 d-flex justify-content-center flex-column align-items-center">

                                                    <h6>Our Live Host</h6>

                                                    @if(file_exists(public_path('uploads/users/'.$webinar->CoachDetial->id.'/'.$webinar->CoachDetial->profile_photo))
                                                    && isset($webinar->CoachDetial->profile_photo))
                                                    <img src="{{url('uploads/users/'.$webinar->CoachDetial->id.'/'.$webinar->CoachDetial->profile_photo)}}"
                                                        class="webinar-logo-img rounded" alt="no host img">
                                                    @endif
                                                    <p class="mt-3">{{$webinar->CoachDetial->full_name}}
                                                    </p>
                                                </div>
                                                <!--  <div class="col-6 d-flex align-items-center column-flex" >
                                                    <p>{{$webinar->CoachDetial->full_name}} 
                                                     </p>
                                                    
                                                </div> -->
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                @endforeach
                                @endif


                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- UserRegistrationModal -->

<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-white">
                <h5 class="modal-title" id="registrationModalLabel">Registration for </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Handle with js</p>
                <form action="{{url('webinars/registration')}}" method="post">
                    @csrf
                    <input type="hidden" name="webinar_id" id="registrationId" value="">

                    <button type="submit" class="btn btn-primary rounded-pill">Yes Register</button>

                </form>

            </div>

        </div>
    </div>
</div>


<!-- UserCancelRegistrationModal -->

<div class="modal fade" id="registrationCancelModal" tabindex="-1" role="dialog"
    aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-white">
                <h5 class="modal-title" id="registrationModalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Handle with js</p>

                <form action="{{url('webinars/CancelRegistration')}}" method="post">
                    @csrf
                    <input type="hidden" name="webinar_id" id="registrationId" value="">

                    <button type="submit" class="btn btn-danger rounded-pill" style="float:right;">Cancel
                        Registration</button>

                </form>

            </div>

        </div>
    </div>
</div>

<!-- WebinarDetailModal -->
<div class="modal fade" id="WebinarDetailModal" tabindex="-1" role="dialog" aria-labelledby="WebinarDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-white">
                <h5 class="modal-title">Registration for </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <p id="title">Handle title with js</p>
                <p id="startDateTime">Handle startDateTime with js</p>
                <p id="endDateTime">Handle endDateTime with js</p>
                <p id="streamkey">Handle streamkeywith js</p>
                <p id="link">Handle link with js</p>
                <p id="keypoints">Handle keypoints with js</p>

            </div>

        </div>
    </div>
</div>
@include('frontend.common.footer')
@stop

@section('appJs')
<script src="https://unpkg.com/@js-temporal/polyfill/dist/index.umd.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="{{url('js/vendor/jquery-3.3.1.min.js')}}"></script>

<script src="{{url('js/module/webinar.js')}}"></script>
<script>
var selectedwebinar = {
    !!json_encode($selectedWebinar) !!
};
$('#webinar-' + selectedwebinar.id + '-tab').addClass('active');

function removeActiveClaass() {
    $('.sidebarPastWebinars ul li #webinar-' + selectedwebinar.id + '-tab').removeClass('active');

}
$(document).ready(function() {

    function hasChecking() {
        var hashtag = $('#LogosWrapper ul li ' + location.hash + '-tab');
        if (hashtag.hasClass('active')) {
            $('.sidebarPastWebinars ul li #webinar-' + selectedwebinar.id + '-tab').removeClass('active');
        }

    }
    const myTimeout = setTimeout(hasChecking, 150);

    // WebinarModalForCoachs
    $('#WebinarDetailModal').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        var webinar = button.data('webinar');
        var key = button.data('key');
        var output = "";
        for (i = 0; i < key.length; i++) {

            output += "<li>" + key[i].content + "</li>";
        }
        var modal = $(this);
        var startDateTime = ModalDateTimeConverter(webinar.start_datetime);
        var endDateTime = ModalDateTimeConverter(webinar.end_datetime);
        var webinar_link = base_url + "/webinars/play/" + webinar.id;
        modal.find('.modal-title').text('Quick View of ' + webinar.title);
        modal.find('.modal-body #title').html('Webinar Title: <strong> ' + webinar.title + "</strong>");
        modal.find('.modal-body #startDateTime').html('Starting Date & Time: <strong> ' +
            startDateTime + "</strong>");
        modal.find('.modal-body #endDateTime').html('Ending Date & Time: <strong> ' + endDateTime +
            "</strong>");
        modal.find('.modal-body #streamkey').html('Joining key: <strong> ' + webinar.streamKey +
            "</strong>");
        modal.find('.modal-body #link').html('joining link: <strong><a href=' + webinar_link + ">" +
            webinar_link + "</a></strong>");
        modal.find('.modal-body #keypoints').html('Key points: <strong><ul> ' + output +
            "</ul></strong>");

    });
});

$(document).ready(function() {



    // ForRegistrationModal
    $('#registrationModal').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        var webinar_title = button.data('whatever');
        var webinar_id = button.data('id'); // Extract info from data-* attributes registrationId
        var modal = $(this);

        modal.find('.modal-title').text('Confirmation for ' + webinar_title);
        modal.find('.modal-body p').html('Are you sure to register for webinar with title<strong> ' +
            webinar_title + "</strong>.");

        modal.find('.modal-body #registrationId').val(webinar_id);
    });
});

$(document).ready(function() {
    $('#registrationCancelModal').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        var webinar_title = button.data('whatever');
        var webinar_id = button.data('id'); // Extract info from data-* attributes registrationId
        var modal = $(this);
        modal.find('.modal-title').text('Confirmation for ' + webinar_title);
        modal.find('.modal-body p').html(
            'Are you sure to cancel registeraton for webinar with title <strong>' + webinar_title +
            "</strong>.");
        modal.find('.modal-body #registrationId').val(webinar_id);
    });
});

// function for bininding tab id with url
$(function() {
    var hash = window.location.hash;
    hash && $('ul.nav.nav-pills a[href="' + hash + '"]').tab('show');
    $('ul.nav.nav-pills a').click(function(e) {
        $(this).tab('show');
        $('body').scrollTop();
        window.location.hash = this.hash;
    });
});
// ForJustSelectedWebinar

var selectedWebinar = {
    !!json_encode($selectedWebinar) !!
};
var dateTime = selectedWebinar.start_datetime;

function dateConverter(dateTime) {
    var humanDate = new Date(dateTime * 1000);
    var year = humanDate.getFullYear();
    var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
    var date = humanDate.getDate();
    var fulldate = year + '-' + month + '-' + date;

    return fulldate;

}

function timeConverter(dateTime) {
    var wStart_time = new Date(dateTime * 1000).toLocaleString('en-GB', {
        hour12: false,
        //timeZone:'Europe/London',
        timeStyle: 'short',
    });


    return wStart_time;
}

var selectedStartTime = timeConverter(dateTime);
var selectedStartDate = dateConverter(dateTime);
//var selectedCountDownDate = new Date(selectedStartDate + " " + selectedStartTime + ":00").getTime();

var dateTimeFormating = selectedStartDate + " " + selectedStartTime + ":00";
// FormatingDateEspecialyForIOS
var tempCountTime = dateTimeFormating.split(/[- :]/);
// Apply each element to the Date function
var tempDateObject = new Date(tempCountTime[0], tempCountTime[1] - 1, tempCountTime[2], tempCountTime[3], tempCountTime[
    4], tempCountTime[5]);
var selectedCountDownDate = new Date(tempDateObject).getTime();





var selectedCounter = setInterval(function() {

    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = selectedCountDownDate - now;
    document.getElementById("days").innerHTML = Math.floor(distance / (1000 * 60 * 60 * 24)) + " D";
    document.getElementById("hours").innerHTML = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 *
        60)) + " H";
    document.getElementById("minutes").innerHTML = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)) +
        " M";
    document.getElementById("seconds").innerHTML = Math.floor((distance % (1000 * 60)) / 1000) + " S";


    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(selectedCounter);
        var url = window.location.origin + "/webinars/play/" + selectedWebinar.id;
        document.getElementById("days").innerHTML = "<a href=" + url + ">Join</a>";
        document.getElementById("hours").classList.add('d-none');
        document.getElementById("minutes").classList.add('d-none');
        document.getElementById("seconds").classList.add('d-none');
    }
}, 1000);

// ForModalConvertion
function ModalDateTimeConverter(unixdatetime) {

    var webinarStart_time = new Date(unixdatetime * 1000).toLocaleString('en-GB', {
        hour12: false,
        timeZone: 'Europe/London',
        timeStyle: 'long',
    });
    var humanDate = new Date(unixdatetime * 1000);
    var year = humanDate.getFullYear();
    var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
    var date = humanDate.getDate();
    var fulldate = date + '/' + month + '/' + year + ' - ' + webinarStart_time + ':00';
    return fulldate;
}
</script>

@stop

<script>
// For All OtherWebinars
function DateTimeConverter(unixdatetime) {

    var wStart_time = new Date(unixdatetime * 1000).toLocaleString('en-GB', {
        hour12: false,
        // timeZone:'Europe/London',
        timeStyle: 'short',
    });
    var humanDate = new Date(unixdatetime * 1000);
    var year = humanDate.getFullYear();

    var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
    var date = humanDate.getDate();

    var fulldate = year + '-' + month + '-' + date + ' ' + wStart_time + ':00';

    // var dateTimeFormating = selectedStartDate + " " + selectedStartTime + ":00";
    // FormatingDateEspecialyForIOS
    var tempCountTimmer = fulldate.split(/[- :]/);
    // Apply each element to the Date function
    var tempDateObject = new Date(tempCountTimmer[0], tempCountTimmer[1] - 1, tempCountTimmer[2], tempCountTimmer[3],
        tempCountTimmer[4], tempCountTimmer[5]);
    var CountDownDateTime = new Date(tempDateObject).getTime();

    return CountDownDateTime;
}


function timmerCounter(webinar_id, start_datetime) {
    let web_id = webinar_id;
    let humanDateTime = DateTimeConverter(start_datetime);

    var countDownDate = new Date(humanDateTime).getTime();
    var counter = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();
        // Find the distance between now and the count down date
        var timeDistance = countDownDate - now;
        document.getElementById("days-" + web_id).innerHTML = Math.floor(timeDistance / (1000 * 60 * 60 * 24)) +
            " D";
        document.getElementById("hours-" + web_id).innerHTML = Math.floor((timeDistance % (1000 * 60 * 60 *
            24)) / (
            1000 * 60 * 60)) + " H";
        document.getElementById("minutes-" + web_id).innerHTML = Math.floor((timeDistance % (1000 * 60 * 60)) /
            (
                1000 * 60)) + " M";
        document.getElementById("seconds-" + web_id).innerHTML = Math.floor((timeDistance % (1000 * 60)) /
                1000) +
            " S";

        // If the count down is over, write some text 
        if (timeDistance < 0) {
            clearInterval(counter);
            var url = window.location.origin + "/webinars/play/" + web_id;
            document.getElementById("days-" + web_id).innerHTML = "<a href=" + url + ">Join</a>";
            document.getElementById("hours-" + web_id).classList.add('d-none');
            document.getElementById("minutes-" + web_id).classList.add('d-none');
            document.getElementById("seconds-" + web_id).classList.add('d-none');
        }
    }, 1000);
}
</script>