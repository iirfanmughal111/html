@extends('frontend.layouts.master')
@section('pageTitle','Login')
@section('content')
  @include('frontend.common.header')

	<div class="wrapper">
		<section class="sign-up-sec">
			<div class="container">
				<div class="section-hero margin-bottom-60">
					<h1 class="heading-xl">Sign in</h1> </div>
				<div class="container-width-small">
					<div class="form w-form">
						@include('flash-message')
						<form method="POST" action="{{ route('login') }}" class="grid-form" id="login-Form">
						login			{{ csrf_field() }}
							<div class="form-group">
								<label for="Email-Address-2">Email</label>
								<input type="email" class="input w-input" maxlength="256" placeholder="email@example.com" id="Email-Address-2" name="email">

								<div class="error_margin"><span class="error" >  {{ $errors->first('email')  }} </span></div>
							</div>
							<div class="form-group">
								<label for="Password-3">Password</label>
								<input type="password" class="input w-input" maxlength="256" placeholder="supersecurepassword" id="Password-3" required="" name="password">

								<div class="error_margin"><span class="error" >  {{ $errors->first('password')  }} </span></div>
							</div>
							<input type="submit" value="Submit" class="submit-button button-full w-button"> 
						</form>
					</div> 
					{{--<a href="#/" class="form-link">Forgot password?</a> --}}
					<div class="authenticate-link">
			      		<a href="{{'/register'}}" class="formlink"> Register</a><a data-toggle="modal" href="#" data-target="#forget_modal" class="forgot formlink">Forgot my password</a>
			      	</div>
				</div>
			</div>

		</section>
	</div>
	@include('frontend.common.modal')
	@include('frontend.common.footer')
@stop