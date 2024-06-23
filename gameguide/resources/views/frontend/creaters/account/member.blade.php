@extends('frontend.layouts.master')
@section('pageTitle','Members')
@section('headtitle')
| Members
@endsection
@section('additionalcss')
  <link rel="stylesheet" href="{{ url('frontend/css/croppie.css')}}">
  <link rel="stylesheet" href="{{ url('frontend/css/custom.css')}}">
  <link href="{{ url('frontend/css/star-rating-svg.css')}}" rel="stylesheet">
@stop
@section('content')
  @include('frontend.common.header')

  <div class="wrapper">
   

    <section class="guide-grid">
      <div class="container">
          <div class="row text-center">   
            
          
            <h2 style="width:100%;padding:80px 0" class="text-center">Coming Soon...</h2>
      
        </div>    
     
     
      </div>
     
   </section>

  </div>

@include('frontend.common.footer')

@section('additionJs')
  
@stop

@stop
