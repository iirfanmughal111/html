@extends('frontend.layouts.master')
@section('pageTitle', 'Signup')
@section('content')
  @include('frontend.common.header')
  <div class="wrapper">
    {{-- Hidden path  --}}
    @php $hidden_path = 'register'; @endphp
   <section class="sign-up-sec" id="sign-up-sec">
<div class="container">
  
  <div class="section-hero margin-bottom-60">
    <h1 class="heading-xl">Sign up </h1> </div>
  <div class="container-width-small">
    <div class="form w-form">

      @include('flash-message')	

      <form method="POST" action="{{ route('register') }}" id="Signup-Form" name="wf-form-Signup-Form" data-name="Signup Form" class="grid-form" alt="Have You Buyed The Code">
        @csrf

        <div class="form-input-group">
          <label for="access_code">Access code</label>
          <input type="text" class="input w-input" name="access_code" data-name="Access Code" placeholder="Enter Your Access Code" value="{{$accessCode}}" id="access_code">
          <div class="error_margin">
            <span class="access_code_error error error" role="alert">
                {{ $errors->first('access_code') }}
            </span>
          </div>
        </div>

      <div id="Fst_Lst">

        <div class="grid-form-row-halves">
          <div class="form-group">
            <label for="first_name">First name</label>
            <input type="text" class="input w-input" maxlength="256" name="first_name" data-name="First name" placeholder="Jane" id="first_name" value="{{ old('first_name') }}">
            {{--@if ($errors->has('first_name'))--}}
              <div class="error_margin">
                <span class="first_name_error error" role="alert">
                    {{ $errors->first('first_name') }}
                </span>
              </div>
            {{--@endif--}}
          </div>
          <div class="form-group">
            <label for="last_name">Last name</label>
            <input type="text" class="input w-input" maxlength="256" name="last_name" data-name="Last name" placeholder="Doe" id="last_name" value="{{ old('last_name') }}">
            {{--@if ($errors->has('last_name'))--}}
              <div class="error_margin">
                <span class="last_name_error error" role="alert">
                    {{ $errors->first('last_name') }}
                </span>
                </div>
            {{--@endif--}}
          </div>
        </div>

      </div>

        <div class="form-input-group" id="eml">
          <label for="email">Email</label>
          <input type="email" class="input w-input" maxlength="256" name="email" data-name="Email Address" placeholder="email@example.com" id="email" value="">
          {{--@if ($errors->has('email'))--}}
            <div class="error_margin">
              <span class="email_error error" role="alert">
                  {{ $errors->first('email') }}
              </span>
              </div>
          {{--@endif--}}
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="input w-input" maxlength="256" name="password" data-name="Password" placeholder="supersecurepassword" id="password">
          {{--@if ($errors->has('password'))--}}
            <div class="error_margin">
              <span class="password_error error" role="alert">
                  {{ $errors->first('password') }}
              </span>
              </div>
          {{--@endif--}}
        </div>  

        <div class="form-group" id="c_pass">
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" class="input w-input" maxlength="256" name="password_confirmation" data-name="Confirm Password" placeholder="supersecurepassword" id="password_confirmation">
          {{--@if ($errors->has('password_confirmation'))--}}
            <div class="error_margin">
              <span class="password_confirmation_error error" role="alert">
                  {{ $errors->first('password_confirmation') }}
              </span>
              </div>
          {{--@endif--}}
        </div> 

       

        {{--<input type="submit" value="Submit" class="submit-button button-full w-button"> --}}
        <a href="javascript:void(0);" class="submit-button button-full w-button"> 
          <div class="spinner-border register-spinner" role="status" style="display:none;">
            <span class="sr-only">Loading...</span>
          </div>Submit
        </a>
      </form>
    </div> 
      <a href="{{url('login')}}" class="form-link">Already a member?&nbsp;Sign in</a> 
    </div>
</div>

   </section>   
   
</div>
  
  @include('modal.stripeCard')
  @include('modal.paypalCardModel')
  @include('frontend.common.footer')
@stop

@section('additionJs')
  <script src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript">
   var stripeClient = "{{ config('services.stripe.key') }}";
  </script>
    <script src="{!! asset('frontend/js/module/payment-method.js') !!}"></script>
    <script src="{!! asset('frontend/js/module/paypal-payment-method.js') !!}"></script>
    <script src="{{ asset('frontend/js/module/register.js')}}"></script>
@stop