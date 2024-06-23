<section class="guide-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<ul class="nav nav-pills">
				  <li class="nav-item">
					<a class="nav-link {{ Request::is('game-guide*') ? 'active' : '' }}" href="{{('/game-guide')}}">Game guides</a>
				  </li>
				   <li class="nav-item">
					<a class="nav-link {{ Request::is('live-update*') ? 'active' : '' }}" href="{{('/live-update')}}">Live Updates</a>
				  </li>
				 
				  <li class="nav-item">
					<a class="nav-link {{ Request::is('coaches*') ? 'active' : '' }}" href="{{('/coaches')}}">Coaches</a>
				  </li>
				<li class="nav-item">
					<a class="nav-link {{ Request::is('players*') ? 'active' : '' }}" href="{{('/players')}}">Players</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ Request::is('webinars*') ? 'active' : '' }}" href="{{('/webinars')}}">Webinars</a>
				</li>
				  @if((isset(auth::user()->plan_id) && auth::user()->plan_id == 2) || (isset(auth::user()->role_id) && auth::user()->role_id == 3))
					  <li class="nav-item">
						<a class="nav-link {{ Request::is('chat*') ? 'active' : '' }}" href="{{('/chat')}}">Chat</a>
					  </li>
                        
					 <li class="nav-item">
						<a class="nav-link {{ Request::is('plans*') ? 'active' : '' }}" href="{{('/plans')}}">Active Plans</a>
					  </li>
					
				  @endif
				 
				  <li class="nav-item">
					<a class="nav-link" href="{{('/logout')}}">Logout</a>
				  </li>				  
				</ul>
				</div>			
			</div>			
		</div>
</section>