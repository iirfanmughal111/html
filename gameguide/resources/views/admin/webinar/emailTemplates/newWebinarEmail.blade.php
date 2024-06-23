<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Webinar Annoucment</title>
</head>

<body>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12 mt-5">
                <h1 class="bg-dark text-white rounded text-center fw-bold py-3">Webinar Annoucment</h1>
                
            </div>
            <div class="col-8">

            <h2>Save the date & Time:  <span class="fw-bold text-primary">{{ Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toDateTimeString() }} : {{$webinar->start_time}}</span></h2>
<h1>Hi {{$username}},</h1>
{{$status ?? ''}}
<p>We invited our professional and community leader to join us in {{$webinar->title}}.

{{$webinar->desc}}.</p><br>

@if($webinar->keypoints->count())
This event have following major points:
<ul>
@foreach($webinar->keypoints as $key=>$point)
<li>{{$point->content}}</li>
@endforeach
</ul>
@endif
@if($coach==1)
<p>Use this key for hosting: " {{$webinar->streamKey}} "</p>
@endif
<p>Join us at our upcoming event on following scedule.</p>
<h6>Webinar Starting Date & Time: {{ Carbon\Carbon::createFromTimestamp($webinar->start_datetime)->toDateTimeString() }}</h6>
<h6>Webinar Ending Date & Time: {{ Carbon\Carbon::createFromTimestamp($webinar->end_datetime)->toDateTimeString() }}</h6>
<!--<p>You can join the webinar with following link after starting time:</p>
<a href="{{url('webinars/play/'.$webinar->id)}}" class="btn btn-primary">join webinar</a>

<p>For registration <a href="{{url('webinars/'.$webinar->id)}}">click here!</a>.</p>


-->
            </div>
        </div>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>