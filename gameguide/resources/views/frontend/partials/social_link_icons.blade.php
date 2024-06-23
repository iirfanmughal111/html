@php $facebook_link=""; $twitter_link=""; $instagram_link=""; $target="_blank"; @endphp
    @if(isset($className) && $className == 'coache-page')
        @if(isset($coache) && isset($coache->userProfile))
            @if(!empty($coache->userProfile->facebook_link))
                @php $facebook_link=$coache->userProfile->facebook_link; @endphp
            @endif
            @if(!empty($coache->userProfile->instagram_link))
                @php $instagram_link=$coache->userProfile->instagram_link; @endphp
            @endif
            @if(!empty($coache->userProfile->twitter_link))
                @php $twitter_link=$coache->userProfile->twitter_link; @endphp
            @endif

        @endif
    @else
        @if(isset(auth::user()->userProfile))
            @if(!empty(auth::user()->userProfile->facebook_link))
                @php $facebook_link=auth::user()->userProfile->facebook_link; @endphp
            @endif
            @if(!empty(auth::user()->userProfile->instagram_link))
                @php $instagram_link=auth::user()->userProfile->instagram_link; @endphp
            @endif
            @if(!empty(auth::user()->userProfile->twitter_link))
                @php $twitter_link=auth::user()->userProfile->twitter_link; @endphp
            @endif
        @endif
    @endif
	
	
	@if($facebook_link=="")
	@php $facebook_link="javascript:void(0)"; $target="_blank";  @endphp
	@endif
	@if($twitter_link=="")
	@php $twitter_link="javascript:void(0)"; $target="_blank";  @endphp
	@endif
	
	@if($instagram_link=="")
	@php $instagram_link="javascript:void(0)"; $target="_blank";  @endphp
	@endif
    
    <a href="{{$facebook_link}}" target="{{$target}}"><i class="fab fa-facebook"></i></a>
    <a href="{{$instagram_link}}" target="{{$target}}"><i class="fab fa-instagram"></i></a>
    <a href="{{$twitter_link}}" target="{{$target}}"><i class="fab fa-twitter"></i></a>
    @if($className == 'profile-page')
        <a href="javascript:void(0);" class="edit-social-links" data-toggle="modal" data-target="#edit_social_links_modal"> <i class="fas fa-pencil-alt"></i></a>
    @endif