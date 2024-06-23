@extends('frontend.layouts.master')
@section('headtitle')
Webinar
@endsection

@section('content')
@include('frontend.common.header')

<style>
.webinar-title {
    font-weight: 600;
    color: red;
    font-size: 15px;
    margin-bottom: 6px;
    padding-top: 7px;
}

.card-img-top{
    max-height:212px;
    min-height:212px;

}

.details-btn {
    border: 1px solid #8B53FF;
    background-color: #fff;
    color: #8B53FF;
    font-size: 10px;
}

.details-btn:hover {

    background-color: #8B53FF;
    color: white;

}

.webinar-logo-img{
    max-height:50px;
    max-width:50px;
    min-height:50px;
    min-width:50px;
}

</style>

<div class="wrapper">
    @include('frontend.partials.account_top')
    @include('frontend.partials.guide_bar')

    <div class="container pt-3">


        <div class="row mt-3">
            <div class="col-12">
                <h1>Past Webinars</h1>
            </div>

            @if(is_object($pastWebinars) && !empty($pastWebinars) && $pastWebinars->count())

            @foreach($pastWebinars as $key => $webinar)


            <div class="col-lg-3 d-flex justify-content-center col-12 col-md-6 col-sm-6 mt-80">
                <div class="card mb-4 h-100" style="width: 18rem;">
                <a href="{{url('webinars/'.$webinar->id)}}"><img src="{{url('uploads/webinar/featured_images/'.$webinar->featuredImg_image)}}" class="card-img-top" alt="no featured img in database"></a>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4"><img src="{{url('uploads/webinar/logos/'.$webinar->logo_image)}}" class="webinar-logo-img rounded-circle" alt="no logo img in databses"> 
                            </div>
                            <div class="col-8 d-flex align-items-center">
                                <p class="card-text">
                                    <a href="{{url('webinars/'.$webinar->id)}}">
                                <span class="webinar-title">{{$webinar->title}}</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <a href="{{url('webinars/'.$webinar->id)}}" class="btn mt-4 btn-default details-btn">Detials</a>
                        
                    </div>
                </div>

            </div>


            @endforeach

            @else

            No Data Found.
            @endif

        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-center mt-4 mb-2">
                {{$pastWebinars->links()}}
            </div>
        </div>
    </div>
</div>


    @include('frontend.common.footer')
    @stop

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
    < script src = "https://code.jquery.com/jquery-3.5.1.min.js" >
    </script>
    <script src="script.js"></script>
    </script>


    <script>

 

    </script>