<?php header("Access-Control-Allow-Origin: *"); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        @if(auth::user())
        <a class="navbar-brand" href="{{url('user-profile')}}">
            <img class="logo img-fluid" src="{{ url('https://i.imgur.com/h01RzdD.png')}}">
        </a>
        @else
        <a class="navbar-brand" href="/">
            <img class="logo img-fluid" src="{{ url('https://i.imgur.com/h01RzdD.png')}}">
        </a>
        @endif



        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" />
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                @if(!auth::user())

                <li class="nav-item">
                    <a class="nav-link" href="https://www.us-prostreamers.com/tour">Tour</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.us-prostreamers.com/pricing">Members</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.us-prostreamers.com/coaches">Coaches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.us-prostreamers.com/resources">Resources</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://www.us-prostreamers.com/about">About</a>
                </li>

                @endif
                @auth
                @include('frontend.common.lang_switch')
                @include('frontend.playercommunity.notification')

                @endauth
                @if(auth::user())
                <li class="nav-item">
                    <a class="nav-link" href="{{url('user-profile')}}"> My Account</a>

                </li>

                <li class="nav-item">
                    <a href="https://ggradio.net/player" class="nav-link"
                        onclick="window.open(this.href, 'player','left=20,top=20,width=450,height=800,toolbar=0,resizable=0'); return false;">
                        <i class="fas fa-headphones"></i>
                    </a>
                </li>

                {{--<li class="nav-item dropdown user-link">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-user-circle"></i> My Account
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="#">Account</a>
							<a class="dropdown-item" href="#">Features</a>
							<a class="dropdown-item" href="#">Support</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="{{('logout')}}">Sign out</a>
        </div>
        </li> --}}
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{'login'}}">My Account</a>
        </li>
        @endif
        </ul>
    </div>
</nav>
<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en'
    }, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>