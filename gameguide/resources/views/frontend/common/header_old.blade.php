<?php   
	//$currentAction = \Route::currentRouteAction();
    //list($controller, $method) = explode('@', $currentAction); 
   // $controller = preg_replace('/.*\\\/', '', $controller);  
    ?>
     <nav class="navbar navbar-default navbar-fixed-top">
		  <div class="container" id="app1">
			
			<div class="navbar-header">		  
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="{{url('/')}}"><img class="img-responsive" src="<?php echo showSiteTitle("logo") ?>"></a>
			</div>
			
			 @if(auth::user())
			 @if(auth::user()->id !='' && (auth::user()->role_id==2)) 
			<div id="navbar" class="navbar-collapse collapse">
			  <ul class="nav navbar-nav navbar-right">
				<li class="nav-item nav-profile dropdown">
		    	 <a class="nav-link dropdown-toggle" id="profileDropdown" href="{{url('/user-profile')}}" >
				  @php
					$profile_photo =  profile_photo(auth::user()->id);;
				 @endphp
					<div class="nav-profile-img">
					  @if(auth::user()->profile_photo==NULL)
					  <span class="avtar"> {{ ucwords(substr(auth::user()->first_name,0,1)) }} </span>
				      @else
					    <!--img src="{{url('/frontend/images/face1.jpg')}}" alt="image"-->  
					    <img src="{{timthumb($profile_photo,100,100)}}" alt="image">  
				      @endif
					 
					  <!--span class="availability-status online"></span--> 
					</div>
					<div class="nav-profile-text">
					  <p class="mb-1 text-black">{{ucwords(auth::user()->first_name) }} </p>
					</div>
				  </a>
				</li>
				<!--   MESSAGE BOX --->
				
				<li class="dropdown nav-myaccount">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-user" aria-hidden="true"></i> My Account <span class="caret"></span></a>
				  <ul class="dropdown-menu">
				    <li><a href="{{('/edit-profile')}}">Edit Profile</a></li>
					<li><a href="{{('/logout')}}">Logout</a></li>
				  </ul>
				</li>
				
			  </ul>
			</div><!--/.nav-collapse -->
			@endif
			@endif
		  </div>
		</nav>