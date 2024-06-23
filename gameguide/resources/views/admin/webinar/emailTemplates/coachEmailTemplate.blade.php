<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>test Coach page</title>
</head>

<body>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12 mt-5">
                <h1 class="bg-dark text-white rounded text-center fw-bold py-3">Webinar Email Page</h1>
                
            </div>
            <div class="col-8">

            <h2>Save the date & Time:  <span class="fw-bold text-primary">{{$start_date}} : {{$start_time}}</span></h2>
<h1>Hi {{$username}},</h1>
<p>you will be the host for this webinar. your key is: "{{$streamKey}}". Go to OBS and and paste there this key. blah blah </p>

<p>Each year we invite our professionals and community leaders to join us in {{$title}}.

Have you ever wanted to meet up with {Industry leader} or {Industry leader}? This is what we particularly do.

Spend {x} days dedicated to learning, networking and exchanging knowledge with world-class entrepreneurs.

Join us at our upcoming event {Event name} on following scedule.</p>


<h6>Webinar Starting Date: {{$start_date}}</h6>
<h6>Webinar Starting Time: {{$start_time}}</h6>
<h6>Webinar Ending Date: {{$end_date}}</h6>
<h6>Webinar Ending Time: {{$end_time}}</h6>

<a href="http://127.0.0.1:8000/" class="btn btn-primary">join us</a>


<p>Questions? Talk to <a href="http://127.0.0.1:8000/">us!</a></p>


            </div>
        </div>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>