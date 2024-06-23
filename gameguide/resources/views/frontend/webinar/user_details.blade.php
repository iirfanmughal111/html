@extends('frontend.layouts.master')
@section('headtitle')
User Profile
@endsection

@section('content')
@include('frontend.common.header')

<style>
.star-ratings {

    /* background-color: #fff; */
    padding: 54px;
    /* border: 1px solid rgba(0, 0, 0, 0.1); */
    /* box-shadow: 0px 10px 10px #E0E0E0; */
}

.rating-fill{
    font-weight: 900 !important;
}
.stars i {
    font-weight: 100;
    font-size: 18px;
    color: #8B53FF;
}

</style>

<div class="wrapper">
    @include('frontend.partials.account_top')
    @include('frontend.partials.guide_bar')

    <div class="container pt-3">

    @if(isset($user) && isset($userProfile))
        <div class="row mt-3">
            <div class="col-12">
            @include('flash-message')

            </div>
            <div class="col-md-4">
                <img src="{{url('uploads/users/'.$user->id.'/'.$user->profile_photo)}}" class="img-fluid"
                    alt="no logo img in databses">

            </div>
            <div class="col-8">
                <h1>{{$user->full_name}}</h1>
                <p>{{$user->tag_line}}</p>
                <h6>Contact Details</h6>
                <p>{{$user->email}}</p>
                <div class="">
                    <a href="{{$userProfile->facebook_link}}"><i class="fab fa-2x fa-facebook mr-2"></i></a>
                    <a href="{{$userProfile->instagram_link}}"><i class="fab fa-2x fa-instagram mr-2"></i></a>
                    <a href="{{$userProfile->twitter_link}}"><i class="fab fa-2x fa-twitter mr-2"></i></a>
                </div>
                    <p>{{$userProfile->rating}}/5.00</p>
                    <div class="stars">
                        <i id="star-1" class="fa fa-star"></i>
                        <i id="star-2" class="fa fa-star"></i>
                        <i id="star-3" class="fa fa-star"></i>
                        <i id="star-4" class="fa fa-star"></i>
                        <i id="star-5" class="fa fa-star"></i>
                    
                </div>

                    <div class="rating-text">

                        <span>{{$userProfile->total_rating}} ratings & {{$userProfile->total_review}}
                            reviews</span>

                    </div>
                    <p>{{$userProfile->description}}</p>
                    <!-- <p>Total Rating: </p> -->
                    <!-- <div class="w-75 border border-2 border-dark rounded">
                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                            aria-valuenow="{{$userProfile->total_rating}}" aria-valuemin="0" aria-valuemax="100"
                            style="width: {{$userProfile->total_rating}}%">
                            <span>{{$userProfile->total_rating}}%</span>
                        </div>
                    </div> -->

                    <!-- Total Rating: {{$userProfile->total_rating}} <div class="bar-1" style="width: {{$userProfile->total_rating}}%; height: 18px; background-color: #2196F3;"></div> -->

                </div>


            </div>

        </div>


@else
no data
@endif

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
    window.addEventListener('DOMContentLoaded', (event) => {
    var user_Profile = {!! json_encode($userProfile) !!};
if (user_Profile.rating>=0.5){
    document.getElementById('star-1').classList.add('rating-fill');
}
if (user_Profile.rating>=1.5){
    document.getElementById('star-2').classList.add('rating-fill');
}
if (user_Profile.rating>=2.5){
    document.getElementById('star-3').classList.add('rating-fill');


}
if (user_Profile.rating>=3.5){
    document.getElementById('star-4').classList.add('rating-fill');

 
}
if (user_Profile.rating>=4.5){
    document.getElementById('star-5').classList.add('rating-fill');

}
});

</script>