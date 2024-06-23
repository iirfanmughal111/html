<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
    :root {
        --gr-1: linear-gradient(170deg, #01E4F8 0%, #1D3EDE 100%);
        --gr-2: linear-gradient(170deg, #B4EC51 0%, #429321 100%);
        --gr-3: linear-gradient(170deg, #C86DD7 0%, #3023AE 100%);

    }

    @import url('https://fonts.googleapis.com/css?family=Oswald:300,400,500,700');

    @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800');

    /* $gr-1:linear-gradient(170deg, #01E4F8 0%, #1D3EDE 100%);
$gr-2:linear-gradient(170deg, #B4EC51 0%, #429321 100%);
$gr-3:linear-gradient(170deg, #C86DD7 0%, #3023AE 100%); */

    .gr-1 {
        background: var(--gr-1);
    }

    .gr-2 {
        background: var(--gr-2);
    }

    .gr-3 {
        background: var(--gr-3);
    }

    * {
        transition: .5s;
    }

    .h-100 {
        height: 100vh !important;
    }

    .align-middle {
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }

    .project-card-link {
        color: rgba(255, 255, 255, 1);

    }

    .project-card-link:after {
        width: 10%;
    }

    .column {
        margin-top: 3rem;
        padding-left: 3rem;

        &:hover {
            padding-left: 0;

            .card .txt {
                margin-left: 1rem;

                h1,
                p {
                    color: rgba(255, 255, 255, 1);
                    opacity: 1;
                }
            }

            .project-card-link {
                color: rgba(255, 255, 255, 1);

                &:after {
                    width: 10%;
                }
            }
        }
    }

    .card {
        min-height: 170px;
        margin: 0;
        padding: 1.7rem 1.2rem;
        border: none;
        border-radius: 0;
        color: rgba(0, 0, 0, 1);
        letter-spacing: .05rem;
        font-family: 'Oswald', sans-serif;
        box-shadow: 0 0 21px rgba(0, 0, 0, .27);

        .txt {
            margin-left: -3rem;
            z-index: 1;

            h1 {
                font-size: 1.5rem;
                font-weight: 300;
                text-transform: uppercase;
            }

            p {
                font-size: .7rem;
                font-family: 'Open Sans', sans-serif;
                letter-spacing: 0rem;
                margin-top: 33px;
                opacity: 0;
                color: rgba(255, 255, 255, 1);
            }
        }

        .project-card-link {
            z-index: 3;
            font-size: .7rem;
            color: rgba(0, 0, 0, 1);
            margin-left: 1rem;
            position: relative;
            bottom: -.5rem;
            text-transform: uppercase;
            text-decoration: none;

            &:after {
                content: "";
                display: inline-block;
                height: 0.5em;
                width: 0;
                margin-right: -100%;
                margin-left: 10px;
                border-top: 1px solid rgba(255, 255, 255, 1);
                transition: .5s;
            }
        }

        .ico-card {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        i {
            position: relative;
            right: -50%;
            top: 60%;
            font-size: 12rem;
            line-height: 0;
            opacity: .2;
            color: rgba(255, 255, 255, 1);
            z-index: 0;
        }
    }
    </style>
</head>

<body>

    <div class="container h-100">
        <div class="row align-middle">
            @foreach ($webinars as $key=>$webinar)

            <div class="col-md-6 col-lg-4 column">
                <div class="card gr-{{rand(1,3)}}">
                    <div class="txt">
                        <h1>{{$key}}-RANDING AND </br>
                            CORPORATE DESIGN</h1>
                        <p>Visual communication and problem-solving</p>
                    </div>
                    <a href="#" class="project-card-link">more</a>
                    <div class="ico-card">
                        <i class="fa fa-rebel"></i>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-md-6 col-lg-4 column">
                <div class="card gr-2">
                    <div class="txt">
                        <h1>Web Front-End </br>
                            SOLUTIONS</h1>
                        <p>How design is implemented on the web.</p>
                    </div>
                    <a href="#" class="project-card-link">more</a>
                    <div class="ico-card">
                        <i class="fa fa-codepen"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 column">
                <div class="card gr-3">
                    <div class="txt">
                        <h1>UX/UI WEBsite </br>AND MOBILE app</h1>
                        <p>User Interface and User Experience Design.</p>
                    </div>
                    <a href="#" class="project-card-link">more</a>
                    <div class="ico-card">
                        <i class="fa fa-empire"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</body>

</html>