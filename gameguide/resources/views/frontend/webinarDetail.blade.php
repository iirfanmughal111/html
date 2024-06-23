@extends('frontend.layouts.master')
@section('headtitle')
| Webinar
@endsection
@section('chatcss')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.12.0/video-js.min.css" /> 
<script
src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.12.0/video.min.js"></script>

<script
src="https://cdnjs.cloudflare.com/ajax/libs/videojs-youtube/2.6.1/Youtube.min.js"></script>-->
<!-- <link href="{{ url('frontend/css/chat.css')}}" rel="stylesheet"> -->
@stop
@section('content')
@include('frontend.common.header')
<style>

    .webinar-hero {
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        padding: 72px 30px 72px 30px;

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
        margin-top:-50px;
    }
    

    /* #logos-scroll {
        flex-wrap: nowrap !important;
        margin-top: -4%;
    } */

    #prescrollbtn,
    #nextscrollbtn {
        margin-bottom: -8%;
        z-index: 5;
        background-color: blue;
        max-height: 25px;
        color: white;
        border-radius: 50%;
        border: none;
    }
    #prescrollbtn:focus,#nextscrollbtn:focus{
        outline: none !important;
        -webkit-box-shadow: none !important;
    box-shadow: none !important;

    }
    .timmerText{
        font-size: xx-large;
    }
    .dateTimeText{
        font-size: large;
    }

    /* @media (min-width: 158.98px) and (max-width: 375.98px){ 

    .timmerText{
    font-size: large;

    } 
    .dateTimeText{
        font-size: medium;
    }
    #logos-scroll {

    margin-top: -16%;
    } */

    /* #prescrollbtn,
    #nextscrollbtn {
        margin-bottom: -13%;

    } 
    }*/
    /* @media (min-width: 374.98px) and (max-width: 420.98px){ 


    #logos-scroll {

    margin-top: -18%;
    } 
    }*/
        /* #prescrollbtn,
        #nextscrollbtn {
            margin-bottom: -13%;

        }   
        } */
        /* @media (min-width: 400.98px) and (max-width: 575.98px){ 

        
        #logos-scroll {

        margin-top: -15%;
        }
        } */
        /* @media (min-width: 575.98px) and (max-width: 768px){ 

            .timmerText{
            font-size: medium;
            }
            .dateTimeText{
                font-size: medium;
            }
            #logos-scroll {

            margin-top: -14%;
            }

            #prescrollbtn,
            #nextscrollbtn {
                margin-bottom: -15%;

            } 

        }*/
        /* @media (min-width: 1023px)  and (max-width: 1279px) { 
            #logos-scroll {

            margin-top: -6%;
            }

            #prescrollbtn,
            #nextscrollbtn {
                margin-bottom: -9%;

            }
        } */


    /* .past-webinar-img{
    max-width:30px;
    max-height:30px;

    min-width:30px;

    min-height:30px;
    } */

    .sidebar__title {
        /* color: #adadad; */
        /* color: #625454; */

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
    .sidebarPastWebinars ul li {
        margin: 20px 0;
    }
    .sidebarPastWebinars ul li a {
        align-items: center;
        display: flex;
    }
    .sidebarPastWebinars ul li a:hover {
        background-color:white;
    color:black !important;
    text-decoration: none;
    }
    .sidebarPastWebinars ul li a:active {
        background-color:white;
    color:black !important;
    border:1px solid black;
    text-decoration: none;
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
    /* .nav-link:active {
        background-color: blue !important;
    } */

    /* #LogosWrapper{
        position:absolute;
    }
    #LogosButtons{
        position:relative;
    } */
    /* .video-js .vjs-big-play-button{
        /* margin-top:-10% !important;
        z-index: 10;
    } */
.video_thumbnail{
    display: flex;
    background-repeat: no-repeat;
    justify-content: center;
    align-items: center;
    background-size: cover;
    padding: 100px 80px 100px 80px;
}
.thumbnail_play_icon{
font-size: large;

}
</style>
<div class="wrapper">

    @include('frontend.partials.account_top')

    <div class="main-container" id="app">
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 d-md-block d-none sidebarPastWebinars pt-5" style="background-color:#ededf7;">
                    <h6 class="sidebar__title">Past Webinars</h6>
                    <ul>

                    
            @foreach($pastWebinars as $key=>$p_webinar)
                <li class="ml-1"><a href="{{url('/webinars/detail/'.$p_webinar->id)}}"><span><img src="{{url('uploads/webinar/logos/'.$p_webinar->logo_image)}}" class="mr-2 icon"  alt="no logo img in databses"></span><span class="iconText">{{$p_webinar->title}}</span></a></li>
            @endforeach 
        </ul>
            <a href="{{url('webinars/past')}}">See more</a>

                </div>
                <div class="col-md-10">

            
            
            <div class="row">
    @include('frontend.partials.guide_bar')

               
                
                
            </div>
            <div class="row mt-5" >
                <div class="col-12 mb-5 mt-2" id="LogosWrapper" >
                <div class="col-12 d-flex justify-content-between" id="LogosButtons">
                    <button id="prescrollbtn" class=" " type="button"> 
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill"
                            viewBox="0 0 16 16">
                            <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                        </svg>
                    </button>
                    <button id="nextscrollbtn" class=" btn-sm btn-primary rounded-circle"     type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                        </svg>
                    </button>
                </div>
                
                    <ul class="nav justify-content-center" id="logos-scroll">

                        @foreach($UpcommingWebinars as $key=>$webinar)
                        <li class="nav-item">
                            <a class="nav-link " href="#webinar-{{$webinar->id}}" data-bs-toggle="tab"
                                onclick="timmerCounter({{$webinar->id}},{{$webinar->start_datetime}},{{$webinar->start_time}});getUsers({{$webinar->id}});">
                                <!-- {{$webinar->title}} -->
                                <img src="{{url('uploads/webinar/logos/'.$webinar->logo_image)}}"
                                    class="webinar-logo-img mx-4 rounded" alt="no img">
                            </a>
                        </li>

                        @endforeach
                    </ul>




                </div>
            </div>
            <div class="row">
                <!-- <div class="col-2">
                    <nav class="nav flex-column mt-3">
                        @foreach($UpcommingWebinars as $key=>$webinar)
                        <a class="nav-link  my-2 " role="button" data-bs-toggle="tab"
                            href="#webinar-{{$webinar->id}}" onclick="timmerCounter({{$webinar->id}},{{$webinar->start_date}},{{$webinar->start_time}},)">{{$webinar->title}}</a>
                        @endforeach
                        

                    </nav>
                    
                </div> -->
                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane active fade show" id="webinar-{{$selectedWebinar[0]->id}}">
                            <div class="row mb-4">
                                <div class="col-md-6 d-flex justify-content-center">

                                    <h6 class="text-center dateTimeText">Start : <span
                                            class="text-primary pr-1 ml-2">
                                            <!-- {{ Carbon\Carbon::createFromTimestamp($selectedWebinar[0]->start_time)->toTimeString() }} -->
                                            <!-- - -->
                                            <!-- {{ Carbon\Carbon::createFromTimestamp($selectedWebinar[0]->start_date)->toDateString() }} -->
                                            {{ Carbon\Carbon::createFromTimestamp($selectedWebinar[0]->start_datetime)->toDateTimeString() }}
                                        </span>
                                    </h6>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center">

                                    <h6 class="text-center dateTimeText">End : <span
                                            class="text-primary  ml-2">
                                            {{ Carbon\Carbon::createFromTimestamp($selectedWebinar[0]->end_datetime)->toDateTimeString() }}


                                        </span>
                                    </h6>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 webinar-hero"
                                    style="background-image:url({{url('uploads/webinar/featured_images/'.$selectedWebinar[0]->featuredImg_image)}}">
                                    <h2 class="mb-3">
                                        <span class="timmerText">{{$selectedWebinar[0]->title}}</span>
                                    </h2>
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="selectedWebinar-{{$selectedWebinar[0]->id}}"
                                                class="webinar_timer mt-4 mb-5 ">


                                                <strong>
                                                    <span class="bg-white  timmerText p-1 p-sm-3 rounded text-dark" id="days"></span>
                                                    <span class="bg-white  timmerText p-1 p-sm-3 rounded text-dark" id="hours"></span>
                                                    <span class="bg-white  timmerText p-1 p-sm-3 rounded text-dark" id="minutes"></span>
                                                    <span class="bg-white  timmerText p-1 p-sm-3 rounded text-dark" id="seconds"></span>
                                                </strong>



                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="row my-5">
                                

                                   
                                <div class="col-12">
                                @include('flash-message')
                                

                                        @if($userRegistered)
                                        <p class="text-center">Already Registerd</p>

                                            @if ($expired==1)
                                            <button type="button" disabled class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#registrationCancelModal" data-id="{{$selectedWebinar[0]->id}}" data-whatever="{{$selectedWebinar[0]->title}}">Cancel registeration</button>
                                        <!-- <a href="{{url('webinars/CancelRegistration/'.$selectedWebinar[0]->id)}}"class="btn btn-lg  btn-primary rounded-pill">Cancel</a> -->
                                            @else
                                            <button type="button" class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#registrationCancelModal" data-id="{{$selectedWebinar[0]->id}}" data-whatever="{{$selectedWebinar[0]->title}}">Cancel registeration</button>
                                        <!-- <a href="{{url('webinars/CancelRegistration/'.$selectedWebinar[0]->id)}}"class="btn btn-lg  btn-primary rounded-pill">Cancel</a> -->
                                            @endif
                                        
                                        @else
                                            @if ($expired==1)
                                                <button type="button"  disabled  class="btn  btn-primary rounded-pill" data-toggle="modal" data-target="#registrationModal" data-id="{{$selectedWebinar[0]->id}}" data-whatever="{{$selectedWebinar[0]->title}}">Click to register</button>
                                            @else
                                                <button type="button" class="btn  btn-primary rounded-pill" data-toggle="modal" data-target="#registrationModal" data-id="{{$selectedWebinar[0]->id}}" data-whatever="{{$selectedWebinar[0]->title}}">Click to register</button>
                                            @endif
                                        
                                        @endif
                             
                                    
                                  
                                </div>
                            </div>
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-6">
                                        @if ($registerdUesrs->count()!=0)
                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>  
                                                    <th scope="col">Users</th>
                                                    <th scope="col">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="webinarUsersBody-{{$selectedWebinar[0]->id}}">
                                                    @foreach($registerdUesrs as $key=>$web)
                                                    <tr>
                                                        @foreach($allUsersList as $ukey=>$user)
                                                    
                                                            @if($web->user_id==$user->id)
                                                                <td>{{$user->first_name}} {{$user->last_name}} </td>
                                                            @endif
                                                        @endforeach

                                                                <td>{{$web->created_at}}</td>
                                                    </tr>
                                                @endforeach

                                                    
                                            </tbody>
                                        </table>
                                        @else
                                        <div >
                                            <p class="text-center">No users registered yet for this webinar</p>
                                        </div> 
                                        
                                        @endif
                                    </div>
                                    <div class="col-md-6 " >
                                    <div class="video_thumbnail" style="background-image:url({{url('uploads/webinar/featured_images/'.$selectedWebinar[0]->featuredImg_image)}});">
                                        
                                    

                                        <a href="{{url('webinars/play/'.$selectedWebinar[0]->id)}}" class="bg-white rounded-circle p-3"><svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" fill="currentColor" class="bi bi-play-fill " viewBox="0 0 16 16">
                                    <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                                    </svg></a>
                                              </div>                                  
                                        <!--  <div  id="videoOverlay-{{$selectedWebinar[0]->id}}" class="" style="height: 340;width: 540;position: absolute;z-index: 5;background-repeat: no-repeat;background-position: center center;background-image:url({{url('uploads/webinar/featured_images/'.$selectedWebinar[0]->featuredImg_image)}}">

                                       </div> -->
                                     <!--   <div>

                                     <video style="pointer-events:;" id="my-video-{{$selectedWebinar[0]->id}}" class="video-js vjs-theme-forest" controls preload="auto" width="540" height="364" data-setup="{}"> 

                                    <source id="myvideo" src="{{$selectedWebinar[0]->webinar_link}}" type="video/youtube" /> </video>-->
                                        <!-- </div> -->
                                    <!-- <video style="pointer-events:;"
                                                    id="my-video"
                                                    class="video-js vjs-theme-forest"
                                                    controls
                                                    preload="auto"
                                                    width="640"
                                                    height="364"
                                                    data-setup="{}">
                                                    <source id="myvideo" src="{{$selectedWebinar[0]->webinar_link}}" type="video/youtube" /> </video> 
                                  </div> -->
 

                                    <!-- <div id="player"></div>

    <script>
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/embed/z4cVuEHeCe8";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          height: '390',
          width: '640',
          videoId: 'M7lc1UVf-VE',
          playerVars: {
            'playsinline': 1
          },
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
          setTimeout(stopVideo, 6000);
          done = true;
        }
      }
      function stopVideo() {
        player.stopVideo();
      }
    </script>
<button onClick="player.playVideo()"> Click me to start a new</button> -->
                                            <!-- <iframe width="560" height="315" src="https://www.youtube.com/embed/P7MZDE4APBM?BlockCopyLink=true" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><br><br> -->
                                            <!-- <iframe id="ytVideo" width="90%" onload="iframeFunction()" height="auto" style="min-height:300px; " src="{{$selectedWebinar[0]->webinar_link}}?controls=0&modestbranding=0&BlockCopyLink=true" pointer='none' title="YouTube video player" frameborder="0"  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><br><br> -->
                                            <!-- <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/O0ATT40G7Rg?autoplay=1&mute=1&enablejsapi=1" style="pointer-events:none;" title="YouTube video player" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                                            <!-- <iframe width="90%" height="auto" style="min-height:300px;" src="{{$selectedWebinar[0]->webinar_link}}?BlockCopyLink=true" title="YouTube video player" frameborder="0"  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen BlockCopyLink='true'></iframe><br><br>  -->
                                             <!-- <iframe width="90%" height="auto"  style="min-height:300px;" src="{{$selectedWebinar[0]->webinar_link}}?modestbranding=1" title="YouTube video player" frameborder="0"  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                                        
                                            <br><br>
                                            <div class="row">
                                                <div class="col-6">
                                                    
                                                              <h6>Our Live Host</h6>
                                                          
                                                             <img src="{{url('uploads/users/'.$host_detail[0]->id.'/'.$host_detail[0]->profile_photo)}}"
                                    class="webinar-logo-img mx-5 rounded" alt="no host img">
                                                </div>
                                                <div class="col-6 d-flex align-items-center column-flex" >
                                                    <p>{{$host_detail[0]->first_name}} 
                                                    {{$host_detail[0]->last_name}} </p>
                                                    
                                                </div>
                                            </div>
                                  
                                            
                                    </div>

                                </div>

                        </div>


                        @foreach($UpcommingWebinars as $key=>$webinar)
                        <div class="tab-pane mt-3 fade" id="webinar-{{$webinar->id}}" role="tabpanel"
                            aria-labelledby="webinar-{{$webinar->id}}-tab">
                            <div class="row ">
                                <div class="col-md-6 d-flex justify-content-center">

                                    <h6 class="text-center dateTimeText ">Start : <span
                                            class="text-primary pr-1 ml-2">
                                            {{ Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toDateTimeString() }}

                                            <!-- {{ Carbon\Carbon::createFromTimestamp($webinar->start_time)->toTimeString() }} -->
                                            <!-- - -->
                                            <!-- {{ Carbon\Carbon::createFromTimestamp($webinar->start_date)->toDateString() }} -->
                                        </span></h6>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center">

                                    <h6 class="text-center dateTimeText pl-1">End : <span
                                            class="text-primary  ml-2">
                                            <!-- {{ Carbon\Carbon::createFromTimestamp($webinar->end_time)->toTimeString() }} -->
                                            <!-- - -->
                                            <!-- {{ Carbon\Carbon::createFromTimestamp($webinar->end_date)->toDateString() }} -->
                                            {{ Carbon\Carbon::createFromTimestamp($webinar->end_datetime)->toDateTimeString() }}

                                        </span></h6>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 webinar-hero"
                                    style="background-image:url({{url('uploads/webinar/featured_images/'.$webinar->featuredImg_image)}}">
                                    <h2 class="mb-3">
                                        <span class="timmerText ">{{$webinar->title}}</span>
                                    </h2>
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="webinar-{{$webinar->id}}" class="webinar_timer my-2">


                                                <strong>
                                                    <span class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                        id="days-{{$webinar->id}}"></span>
                                                    <span class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                        id="hours-{{$webinar->id}}"></span>
                                                    <span class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                        id="minutes-{{$webinar->id}}"></span>
                                                    <span class="bg-white timmerText p-1 p-sm-3 rounded text-dark"
                                                        id="seconds-{{$webinar->id}}"></span>
                                                </strong>



                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                                <div class="row my-5">
                                    <div class="col-12">

                                                <div id="registerdMsg-{{$webinar->id}}">
                                                    <p class="text-center ">Already Registerd</p>
                                                    <div id="ActiveCancelReg-btnDiv-{{$webinar->id}}">
                                                        <button type="button"  class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#registrationCancelModal" data-id="{{$webinar->id}}" data-whatever="{{$webinar->title}}">Cancel registeration</button>
                                                    </div>
                                                    <div id="DisabledCancelReg-btnDiv-{{$webinar->id}}">
                                                        <button type="button" disabled class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#registrationCancelModal" data-id="{{$webinar->id}}" data-whatever="{{$webinar->title}}">Cancel registeration</button>
                                                    </div>

                                                </div>
                                                <div id="ActiveReg-btnDiv-{{$webinar->id}}">
                                                    <button type="button" class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#registrationModal" data-id="{{$webinar->id}}" data-whatever="{{$webinar->title}}" id="registerdBtn-{{$webinar->id}}" data-action="registration">Click to register</button> 
                                                </div>
                                                <div id="DisabledReg-btnDiv-{{$webinar->id}}">
                                                    <button type="button" disabled class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#registrationModal" data-id="{{$webinar->id}}" data-whatever="{{$webinar->title}}" id="registerdBtn-{{$webinar->id}}" data-action="registration">Click to register</button> 
                                                </div>


                                    </div>
                                </div>
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-6">

                                        <table class="table" id="webinarUserTable-{{$webinar->id}}">
                                            <thead class="thead-light">
                                                <tr>  
                                                    <th scope="col">Users</th>
                                                    <th scope="col">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="webinarUsersBody-{{$webinar->id}}">
                                               
                                                    
                                            </tbody>
                                        </table>
                                        <p class="text-center d-none" id="emptyUsersList-{{$webinar->id}}">No users registered yet for this webinar</p>
                                    </div>
                                    <div class="col-md-6 ">
                                    <div class="video_thumbnail" style="background-image:url({{url('uploads/webinar/featured_images/'.$webinar->featuredImg_image)}});">
                                        
                                    

                                        <a href="{{url('webinars/play/'.$webinar->id)}}" class="bg-white rounded-circle p-3"><svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" fill="currentColor" class="bi bi-play-fill " viewBox="0 0 16 16">
                                    <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                                    </svg></a>
                                              </div>  
                                    <!-- <div  id="videoOverlay-{{$webinar->id}}" class="" style="height: 340;width: 540;position: absolute;z-index: 0;background-repeat: no-repeat;background-position: center center;background-image:url({{url('uploads/webinar/featured_images/'.$webinar->featuredImg_image)}}">

                                        </div>
                                        <div>

                                        <video  id="my-video-{{$webinar->id}}" class="video-js vjs-theme-city" controls preload="auto" width="540" height="364" data-setup="{}">

                                        <source id="myvideo-{{$webinar->id}}" src="{{$webinar->webinar_link}}" type="video/youtube" /> </video>
                                        </div> -->
                                    <!-- <iframe width="90%" height="auto" style="min-height:300px;" src="{{$webinar->webinar_link}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
                                        <div class="row my-4">
                                                <div class="col-sm-6">
                                                
                                                    <h6>Our Live Host</h6>
                                                     <img src="" class="webinar-logo-img mx-5 rounded" alt="no host img available" id="hostImg-{{$webinar->id}}">
                                                </div>
                                                <div class="col-sm-6 d-flex align-items-center column-flex" >
                                                    <p class="ml-sm-0 ml-5" id="hostname-{{$webinar->id}}">Host Name</p>
                                                    
                                                </div>
                                            </div>
                                    </div>

                                </div>
                        </div>
                        @endforeach


                    </div>

                </div>

            </div>
            </div>
            </div>
            <!-- <chat-component :user="{{ Auth::user()->id }}"></chat-component> -->
        </div>
    </div>
</div>
<!-- UserRegistrationModal -->

<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
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
      <!-- <div class="modal-footer d-flex jusify-content-end">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div>


<!-- UserCancelRegistrationModal -->

<div class="modal fade" id="registrationCancelModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
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
                                            
                                            <!-- <button class="btn btn-lg- btn-primary rounded-pill" id="registerdBtn-{{$webinar->id}}">Click to register</button> -->
        <button type="submit" class="btn btn-danger rounded-pill" style="float:right;">Cancel Registration</button>

                                        </form>

      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div>


@include('frontend.common.footer')
@stop

@section('appJs')
<!-- <script src="{{ url('js/app.js?v=').time()}}"></script>		 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="{{url('js/vendor/jquery-3.3.1.min.js')}}"></script>
<script>
    var elements = document.getElementsByClassName("vjs-big-play-button");
//    console.log(elements.length); 
//    elements.addEventListener('click',function(){
//     alert('okd');
//    });

// var elements =   document.querySelectorAll('.vjs-big-play-button').forEach(item => {
//   item.addEventListener('click', event => {
//     var video_overlay = $('#videoOverlay');
//     video_overlay.css("background-image", "");
//     // video_overlay.css("background-color", "transparent !important");
//     video_overlay.css("pointer-events", "none !important");


    
//     console.log('ok '+ item);
   

//   })
// });
$('.vjs-big-play-button').click(function(){
    var vIndex = $('.vjs-big-play-button').index(this);
    //vIndex is the index of buttons out of multiple
    
    //do whatever 
    alert($(this).val());//for value
});
document.querySelectorAll('video').forEach(function(el) {
  el.addEventListener("ended", function() { 
    // this.parentElement.querySelector('.control').className = 'control icon-play';
    alert('finished');
  }, true);
})
// document.getElementById('my-video').addEventListener('ended',myHandler,false);
//     function myHandler(e) {
//         alert('ended')
//         // What you want to do after the event
//     }

function videoEndedFunction(){
    // var video_overlay = $('#videoOverlay');

    // video_overlay.css("background-image", "url({{url('uploads/webinar/featured_images/'.$selectedWebinar[0]->featuredImg_image)}}");
    alert('ended');


}

// for (var i=0; i<elements.length; i++) {
//     console.log(i);
//     elements[i].addEventListener("click", function(){
//     alert('click');});
// }
    // document.getElementsByClassName('.vjs-big-play-button').addEventListener('onclick',function(){
    //     alert('click');
    // });
 

</script>
<script src = "{{url('js/module/webinar.js')}}"></script>
<script>
$(document).ready(function(){
        $('#registrationModal').on('show.bs.modal', function (event) {
            
        var button = $(event.relatedTarget); // Button that triggered the modal
        var webinar_title = button.data('whatever');
        var webinar_id = button.data('id');// Extract info from data-* attributes registrationId
        var modal = $(this);
       
        modal.find('.modal-title').text('Confirmation for ' + webinar_title);
        modal.find('.modal-body p').html('Are you sure to register for webinar with title<strong> ' + webinar_title + "</strong>." );

        modal.find('.modal-body #registrationId').val(webinar_id);
});
});

$(document).ready(function(){
        $('#registrationCancelModal').on('show.bs.modal', function (event) {
            
        var button = $(event.relatedTarget); // Button that triggered the modal
        var webinar_title = button.data('whatever');
        var webinar_id = button.data('id');// Extract info from data-* attributes registrationId
        var modal = $(this);
        modal.find('.modal-title').text('Confirmation for ' + webinar_title);
        modal.find('.modal-body p').html('Are you sure to cancel registeraton for webinar with title <strong>' + webinar_title + "</strong>." );
            alert(webinar_title+" " + webinar_id)
        modal.find('.modal-body #registrationId').val(webinar_id);
});
});

document.body.addEventListener("copy", function(e){
 e.preventDefault();
 e.stopPropagation();
});
document.body.addEventListener("cut", function(e){
 e.preventDefault();
 e.stopPropagation();
});

    function iframeFunction(){
        // var a = $('#ytVideo');
        // var b  = $('.ytp-copylink-icon');
        // console.log(b);
        // b.removeClass('ytp-copylink-icon');
        // // a.removeClass('ytp-copylink-icon2');

        // // a.addClass('ytp-copylink-icon2');
        // alert(a);

    }
</script>

@stop

<script>


    // testingStart
    var UNIX_timestamp ={!! json_encode($selectedWebinar) !!};
    var dateTime = UNIX_timestamp[0].start_datetime;
    // var UNIX_timestamp = JSON.parse("{{ json_encode($selectedWebinar) }}");
    
    function dateConverter(UNIX_timestamp){
        var humanDate = new Date(UNIX_timestamp * 1000);
        // var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var year = humanDate.getFullYear();
        
        var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
        var date = humanDate.getDate();
        var hour = humanDate.getHours();
        var min = humanDate.getMinutes();
        var sec = humanDate.getSeconds();
	// var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
	var fulldate = year + ' ' + month + ' ' + date;
	return fulldate;

  }

function timeConverter(UNIX_timestamp){
	var wStart_time = new Date(UNIX_timestamp * 1000).toLocaleString('en-GB', {
		hour12: false,
		timeZone:'Europe/London',
		timeStyle:'short',
	  });
      
	return wStart_time;
  }
    var selectedStartTime = timeConverter(dateTime);
    var selectedStartDate = dateConverter(dateTime);

function DateTimeConverter(unixdatetime){
    
    var wStart_time = new Date(unixdatetime * 1000).toLocaleString('en-GB', {
		hour12: false,
		timeZone:'Europe/London',
		timeStyle:'short',
	  });
      var humanDate = new Date(unixdatetime * 1000);
        // var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var year = humanDate.getFullYear();
        
        var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
        var date = humanDate.getDate();
        var hour = humanDate.getHours();
        var min = humanDate.getMinutes();
        var sec = humanDate.getSeconds();
	// var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
	var fulldate = year + ' ' + month + ' ' + date + ' ' +wStart_time+':00' ;
    // alert (year + ' ' + month + ' ' + date);

    return fulldate;
}
    // alert(selectedStartDate+" "+selectedStartTime);
  
    // testtingEnd

    // selectedWebinarCounter
// const dateObject = new Date({{$selectedWebinar[0]->start_date}} * 1000);
// const timeObject = new Date({{$selectedWebinar[0]->start_time}} * 1000);


// let selectedWebinarStart_time = timeObject.toLocaleString('en-GB', {
//     hour12: false,
//     timeZone:'Europe/London',
//     timeStyle: 'short',
// });

// let selectedWebinarStart_date = dateObject.getFullYear() + " " + (dateObject.getMonth() + 1) + " " + dateObject
// .getDate();
// alert(selectedStartDate+" "+selectedStartTime);
var countDownDate = new Date(selectedStartDate + " " + selectedStartTime + ":00").getTime();

var counter = setInterval(function() {

    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;
    document.getElementById("days").innerHTML = Math.floor(distance / (1000 * 60 * 60 * 24)) + " D";
    document.getElementById("hours").innerHTML = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 *
        60)) + " H";
    document.getElementById("minutes").innerHTML = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)) +
        " M";
    document.getElementById("seconds").innerHTML = Math.floor((distance % (1000 * 60)) / 1000) + " S";


    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(counter);
        document.getElementById("days").innerHTML = 0 + " D";
        document.getElementById("hours").innerHTML = 0 + " H";
        document.getElementById("minutes").innerHTML = 0 + " M";
        document.getElementById("seconds").innerHTML = 0 + " S";
    }
}, 1000);


// document.getElementsByClassName("ytp-watch-later-button ytp-button ytp-show-watch-later-title")[0].style.display = 'none';
// document.getElementsByClassName("ytp-button ytp-share-button ytp-share-button-visible ytp-show-share-title")[0].style.display = 'none';

 
// For All OtherWebinars
function timmerCounter(webinar_id, start_date, start_time) {
    // const dateObject = new Date(start_date * 1000);
    // const timeObject = new Date(start_time * 1000);
    const web_id = webinar_id;
    // alert(webinar_id);


    // let web_time = timeObject.toLocaleString('en-GB', {
    //     hour12: false,
    //     // timeZone:'Europe/London',
    //     timeStyle: 'short',
    // });
    // let web_date = dateObject.getFullYear() + " " + (dateObject.getMonth() + 1) + " " + dateObject.getDate();
    // alert(web_date+" "+web_time+":00");
    // alert(start_date);
    let humanDateTime = DateTimeConverter(start_date);

    // let web_time = timeConverter(start_date);
    // alert(datetime);

    var countDownDate = new Date(humanDateTime).getTime();

    var counter = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;
        document.getElementById("days-" + web_id).innerHTML = Math.floor(distance / (1000 * 60 * 60 * 24)) +
            " D";
        document.getElementById("hours-" + web_id).innerHTML = Math.floor((distance % (1000 * 60 * 60 * 24)) / (
            1000 * 60 * 60)) + " H";
        document.getElementById("minutes-" + web_id).innerHTML = Math.floor((distance % (1000 * 60 * 60)) / (
            1000 * 60)) + " M";
        document.getElementById("seconds-" + web_id).innerHTML = Math.floor((distance % (1000 * 60)) / 1000) +
            " S";


        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(counter);
            document.getElementById("days-" + web_id).innerHTML = 0 + " D";
            document.getElementById("hours-" + web_id).innerHTML = 0 + " H";
            document.getElementById("minutes-" + web_id).innerHTML = 0 + " M";
            document.getElementById("seconds-" + web_id).innerHTML = 0 + " S";
        }
    }, 1000);
}
</script>