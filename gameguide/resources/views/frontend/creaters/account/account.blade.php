@extends('frontend.layouts.master')
@section('pageTitle','Edit Profile')
@section('headtitle')
| User Profile
@endsection
@section('additionalcss')
<link rel="stylesheet" href="{{ url('frontend/css/croppie.css')}}">
<link rel="stylesheet" href="{{ url('frontend/css/custom.css')}}">
<link href="{{ url('frontend/css/star-rating-svg.css')}}" rel="stylesheet">
@stop
@section('content')
@include('frontend.common.header')

<div class="wrapper">
    @include('frontend.partials.profile_top')
    @include('frontend.partials.guide_bar')

    <section class="guide-grid">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="section-title">Popular Guides</h3>
                        </div>
                        @php $limitText = 60; @endphp
                        @if(isset($games) && count($games)>0)
                        @foreach($games as $game)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="grid-box">
                                <div class="grid-image">
                                    <a href="{{url('game-guide')}}/{{$game->slug}}" class="d-block">
                                        <img class="img-fluid" src="{{$game->image_url}}" alt="image"></a>
                                </div>
                                <div class="grid-content">
                                    <h4><a href="{{url('game-guide')}}/{{$game->slug}}">{{$game->title}}</a></h4>
                                    <p>
                                        {!! substr($game->short_description,0, $limitText) !!}
                                        @if(strlen($game->short_description) > $limitText)
                                        ...
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif


                    </div>
                </div>

                @if(isset($coaches) && count($coaches)>0)
                <div class="col-lg-3">
                    <h3 class="section-title">Popular Trainers</h3>
                    <ul class="coaches-list">
                        @foreach($coaches as $key=>$coache)
                        <li>
                            <div class="pic">
                                <a href="{{'/coache-details'}}/{{$coache->hash}}">
                                    @if(isset($coache->userProfile) && !empty($coache->userProfile->coache_photo))
                                    <img alt="Profile Picture" src="{{$coache->userProfile->coache_photo_url}}">
                                    @else
                                    <img alt="Profile Picture" src="{{$coache->profile_photo_url}}">
                                    @endif
                                </a>
                            </div>
                            <div class="content">
                                <a href="{{'/coache-details'}}/{{$coache->hash}}">
                                    <h6>{{$coache->full_name}}</h6>
                                </a>
                                @if(isset($coache->userProfile) && !empty($coache->userProfile->rating))
                                <div class="coache-rating" data-rating="{{$coache->userProfile->rating}}"></div>
                                {{--<span class="ratings">
                          @for($i=1;$i<=$coache->userProfile->rating;$i++)
                            <i class="fa fa-star" aria-hidden="true"></i>
                          @endfor
                        </span>--}}
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>


        </div>
        @include('frontend.partials.modal.editSocialLinks')
        @include('frontend.partials.modal.editDescriptionModal')
    </section>

</div>

@include('frontend.common.footer')

@section('additionJs')
<script src="//cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('user_description', {
    allowedContent: true
});
</script>
<script src="{{ url('frontend/js/croppie.js')}}"></script>
<script src="{{ asset('frontend/js/jquery.star-rating-svg.js')}}"></script>
<script src="{{ url('frontend/js/module/profile.js')}}"></script>
@stop

@stop