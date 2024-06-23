@if(auth::user()->banner_photo==NULL)
@php
$banner_photo = url('frontend/images/game_guide_banner.png');
@endphp
@else
@php
$banner_photo = banner_photo(auth::user()->id);
@endphp
@endif
@php
$className = 'other-page';
@endphp
@if(Request::segment(1) == 'user-profile')
@php
$className = 'profile-page';
@endphp
@elseif(Request::segment(1) == 'coache-details')
@php
$className = 'coache-page';
@endphp
@endif
<section class="profile-banner" style="background-image:url({{$banner_photo}})">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-holder">
                    <div class="profile-image">
                        @if(auth::user()->profile_photo==NULL)
                        <img src="{{ url('frontend/images/user-profile.png')}}">
                        @else
                        @php
                        $photo = profile_photo(auth::user()->id);
                        @endphp
                        <img class="profile_photo_change" src="{{$photo}}">
                        {{--<img class="profile_photo_change" src="{{timthumb($photo,140,140)}}">--}}
                        @endif
                    </div>
                    <div class="profile-info">
                        @include('frontend.partials.name_edit')
                        {{--<h3 class="name">{{auth::user()->first_name}} {{auth::user()->last_name}}</h3>
                        <div class="p-social">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('frontend.partials.tag')

</section>