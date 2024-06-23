<div class="menu">
	<div class="main-menu">
		<div class="scroll">
		
		
		 @php    
			$roleArray = Config::get('constant.role_id');
			$dashboardactive='';  $custactive='';	 $configactive='';  	$roleactive='';$cmsactive='';$gameactive='';$gameguideactive=''; $tournamentActive='';$accesscodeactive='';$accesslogactive='';
			
			$emailactive ='';$site_sactive ='';$accactive  ='';	$webactive  ='';
		 @endphp
		 
		 @if(collect(request()->segments())->last()=='account')
		 @php
	      $accactive ='active'
	     @endphp
		 @endif
		
		  @if(collect(request()->segments())->last()=='settings')
		 @php
	      $configactive ='active'
	     @endphp
		 @endif
		  @if(collect(request()->segments())->last()=='dashboard')
		 @php
	      $dashboardactive ='active'
	     @endphp
		 @endif
		  
		  @if(collect(request()->segments())->last()=='customers')
		 @php
	      $custactive ='active'
	     @endphp
		 @endif

		 @if(collect(request()->segments())->last()=='games')
		 @php
	      $gameactive ='active'
	     @endphp
		 @endif

		 @if(collect(request()->segments())->last()=='game-guides')
		 @php
	      $gameguideactive ='active'
	     @endphp
		 @endif
		  
			@if(collect(request()->segments())->last()=='access-codes')
				@php
				$accesscodeactive ='active'
				@endphp
						@endif
			
			
						@if(collect(request()->segments())->last()=='access-logs')
					@php
					$accesslogactive ='active'
					@endphp
							@endif	
			
			
			
		  @if(collect(request()->segments())->last()=='emails')
		 @php
	      $emailactive ='active'
	     @endphp
		 @endif
		 @if(collect(request()->segments())->last()=='roles')
		 @php
	      $roleactive ='active'
	     @endphp
		 @endif

		@if(collect(request()->segments())->last()=='tournaments')
			@php
				$tournamentActive ='active'
			@endphp
		@endif
		 
		@if(collect(request()->segments())->last()=='cms-pages')
		 @php
	      $cmsactive ='active'
	     @endphp
		 @endif

		 @if(collect(request()->segments())->last()=='webinar')
		 @php
	      $webactive ='active'
	     @endphp
		 @endif
		 
			<ul class="list-unstyled">
				
					{{--@if(check_role_access('dashboard_listing'))
					<li class="{{$dashboardactive}}">
						<a href="{{url('/admin/dashboard')}}">
							<i class="simple-icon-home"></i>
							<span>Dashbaord</span>
						</a>
					</li>
					@endif--}}
					@if(current_user_role_id() != 1)
					<li class="{{$accactive}}">
						<a href="{{url('/admin/account')}}">
							<i class="iconsminds-user"></i> 
							<span>Account</span>
						</a>
					</li>
					@endif
					@if(check_role_access('webinar_listing'))

						<li class="{{$webactive}}">
							<a href="{{url('/admin/webinar')}}">
								<i class="iconsminds-film"></i>
								<span>Webinars</span>
							</a>
						</li>
					@endif
						
					@if(check_role_access('customer_listing'))
						<li class="{{$custactive}}">
							<a href="{{url('/admin/customers')}}">
								<i class="simple-icon-user"></i>
								<span>Customers</span>
							</a>
						</li>
					@endif
						

					@if(check_role_access('game_listing'))
						<li class="{{$gameactive}}">
							<a href="{{url('/admin/games')}}">
								<i class="iconsminds-gamepad-2"></i>
								<span>Games</span>
							</a>
						</li>
					@endif

					@if(check_role_access('game_guides_listing'))
						<li class="{{$gameguideactive}}">
							<a href="{{url('/admin/game-guides')}}">
								<i class="iconsminds-folder-edit"></i>
								<span>Games Guides</span>
							</a>
						</li>
					@endif
					<li class="{{$accesslogactive}}">
<a href="{{url('/admin/access-logs')}}">
<i class="iconsminds-key-lock"></i>
<span>Access Log</span>
</a>
</li>			
				
				
				<li class="{{$accesscodeactive}}">
<a href="{{url('/admin/access-codes')}}">
<i class="iconsminds-key-lock"></i>
<span>Access Codes</span>
</a>
</li>
				
				

					@if(check_role_access('tournament_listing'))
						<li class="{{$tournamentActive}}">
							<a href="{{url('/admin/tournaments')}}">
								<i class="iconsminds-folder-edit"></i>
								<span>Tournament</span>
							</a>
						</li>
					@endif
					
					@if(check_role_access('email_listing'))
					<li class="{{$emailactive}}">
						<a href="{{url('/admin/emails')}}">
							<i class="iconsminds-mail"></i>
							<span>Email</span>
						</a>
					</li>
					@endif
					@if(check_role_access('config_listing'))
					<li class="{{$configactive}}">
						<a href="{{url('/admin/settings')}}">
							<i class="simple-icon-settings"></i>
							<span>Config</span>
						</a>
					</li>
					@endif
					{{--@if(check_role_access('roles_listing'))
					<li class="{{$roleactive}}">
						<a href="{{url('/admin/roles')}}">
							<i class="simple-icon-organization"></i>
							<span>Roles</span>
						</a>
					</li>
					@endif--}}
					@if(check_role_access('cms_pages_listing'))
					<li class="{{$cmsactive}}">
						<a href="{{url('/admin/cms-pages')}}">
							<i class="simple-icon-docs"></i>
							<span>CMS Pages</span>
						</a>
					</li>
					@endif

			</ul>
		</div>
	</div>    
</div>