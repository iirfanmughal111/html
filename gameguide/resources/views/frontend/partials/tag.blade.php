<div class="container user-content ">
				<div class="row">
				
				@php
						$className =  'other-page';
					@endphp
					@if(Request::segment(1) == 'user-profile')
						@php
							$className =  'profile-page';
						@endphp
					@elseif(Request::segment(1) == 'coache-details')
						@php
							$className =  'coache-page';
						@endphp
					@endif
					
					<div class="col user-desc" align="center" >
					
					@if($className == 'profile-page')


							
							<p>		{{auth::user()->tag_line}} </p>
							
							
							
								@elseif($className == 'coache-page')

								@if(isset($coache))
							<p>		{{$coache->tag_line}}  </p>
								@endif
								@else

							<p>	{{auth::user()->tag_line}}  </p>
								@endif
						
					</div>

	   </div>
 </div>