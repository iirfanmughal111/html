@extends('frontend.layouts.master')
@section('pageTitle','Plans')
@section('headtitle')
| Plans
@endsection
@section('content')
	@include('frontend.common.header')

	<div class="wrapper">
		@include('frontend.partials.account_top')
        @include('frontend.partials.guide_bar')

        {{-- Hidden path  --}}
        @php $hidden_path = 'plan'; @endphp

		<section class="game-plan-grid">
      <div class="container">		 
<div class="row">
			<div class="col-md-12">
			    <h3 class="section-title text-center mb-5">Game Plans</h3>
			</div>
		</div>
<div  class="row justify-content-center plan-row">
            @if(isset($plan) && count($plan) > 0)
            @csrf
                @foreach($plan as $plandata)
                    @if($plandata->id == 1)
                        <!-- Pricing Block -->
                        <div class="pricing-block col mb-5">
                            <div class="inner-box d-flex flex-column">
                                <a href="javascript:void(0);" class="label-box">10 EURO / MONTH</a>
                                <h3 class="title">{{$plandata->name}}</h3>
                                <p>{!! $plandata->description !!}</p>
                                <ul class="features">
                                    <li><i class="fa fa-check" aria-hidden="true"></i>Access to all videos</li>
                                    <li><i class="fa fa-check" aria-hidden="true"></i>Social media share</li>
                                </ul>
                                <div class="btn-box">
                                    @if(auth::user()->plan_id == 1)
                                        <a href="javascript:void(0);" class="theme-btn">Current Plan</a>
                                    @else
                                       <a href="javascript:void(0);" class="theme-btn updatePlan" data-paypal="{{$plandata->paypal_plan_id}}" data-cost="{{$plandata->amount}}" data-stripe="{{$plandata->stripe_plan_id}}" data-planid="{{$plandata->id}}">Downgrade Plan</a> 
                                    @endif
                                </div>
                            </div>
                        </div>
                   
                    @endif

                @endforeach
            @endif
        </div>
		 
      </div>
   </section>
	</div>

    @include('modal.paymentmethodModel')
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
    <script src="{{ asset('frontend/js/module/plan.js')}}"></script>
@stop