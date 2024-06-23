@extends('frontend.layouts.master')
@section('headtitle')
Playing Webinar
@endsection

@section('content')
@include('frontend.common.header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.12.0/video-js.min.css" />
<script
src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.12.0/video.min.js"></script>

<script
src="https://cdnjs.cloudflare.com/ajax/libs/videojs-youtube/2.6.1/Youtube.min.js"></script>
<style>
iframe{
  /* min-height:78vh !important;
   */
   pointer-events:none !important;
}

.video-container {
  width: 1000px;
  min-height: 1200px;
  margin: auto;
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 15px;
  padding-top: 2px;
  //border:1px solid black;
}
.heading {
  height: 30px;
  width: 100%;
  background: #21303c;
  display: flex;
  align-items: center;
  padding-left: 10px;
  color: white;
  fot-weight: bold;
  font-size: 18px;
}

.video-player {
  width: 100%;
  min-height: 190px;
  //border:1px solid white;
  background: #21303c;
  position: relative;
}

/* #p-p {
  border: none;
  background: no-repeat;
  color: #fff;
  font-size: 50px;
  position: absolute;
  opacity: 0.3;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  cursor: pointer;
} */

/* #p-p-s {
  font-size: 80px;
} */

/* .size-btn {
  width: 20px;
} */

/* #size-btn {
  opacity: 0;
  transition: 0.3s ease;
  position: absolute;
  right: 0.5%;
  bottom: 7.7%;
  cursor: pointer;
} */

.video-player video {
  width: 100%;
  min-height: 190px;
  cursor: pointer;
}

.video-controls {
  width: 100%;
  height: 40px;
  //border:1px solid white;
  background: -webkit-linear-gradient(
    top,
    rgb(87, 156, 206) 0%,
    rgb(58, 118, 168) 53%,
    rgb(48, 116, 179) 100%
  );
  background: -o-linear-gradient(
    top,
    rgb(87, 156, 206) 0%,
    rgb(58, 118, 168) 53%,
    rgb(48, 116, 179) 100%
  );
  background: -ms-linear-gradient(
    top,
    rgb(87, 156, 206) 0%,
    rgb(58, 118, 168) 53%,
    rgb(48, 116, 179) 100%
  );
  background: -moz-linear-gradient(
    top,
    rgb(87, 156, 206) 0%,
    rgb(58, 118, 168) 53%,
    rgb(48, 116, 179) 100%
  );
  background: linear-gradient(
    to bottom,
    rgb(87, 156, 206) 0%,
    rgb(58, 118, 168) 53%,
    rgb(48, 116, 179) 100%
  );
  display: flex;
  justify-content: space-between;
  position: absolute;
  bottom: 0px;
}

/* .p-bar-main {
  position: absolute;
  height: 10px;
  width: 100%;
  border: 1px solid #50a6e1;
  padding: 2px;
} */

/* .left-con {
  display: flex;
  width: 88.5%;
  //width:135px;
  height: 40px;
  //border:1px solid black;
  align-items: center;
  margin-right: 20px;
} */

/* .p-con {
  position: relative;
  width: calc(100% - 55px);
  margin-left: 15px;
  padding-bottom: 7px;
  -webkit-box-shadow: 0px 1px 6px 0px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 1px 6px 0px rgba(0, 0, 0, 0.75);
  box-shadow: 0px 1px 6px 0px rgba(0, 0, 0, 0.75);
  cursor: pointer;
} */

/* .p-bar {
  background: white;
  height: 100%;
  width: 100%;
  cursor: pointer;
  overflow: hidden;
} */

.bar-fill {
  height: 100%;
  background: black;
  width: 0%;
}

.play-pause {
  width: 55px;
  height: 25px;
  border: 1px solid #50a6e1;
  border-radius: 5px;
  margin-left: 3px;
  display: flex;
}

.play,
.pause {
  width: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  font-size: 11px;
  color: white;
  //filter: drop-shadow(1px 1px 1px #000);
}

.play p {
  filter: drop-shadow(1px 1px 1px #000);
}

.pause p {
  filter: drop-shadow(1px 1px 1px #000);
}

/* .pause {
  border-right: 1px solid #50a6e1;
  //pointer-events:none;
} */

#pause {
}

.right-con {
  display: flex;
  //width:140px;
  //width: 47.7%;
  height: 40px;
  //border:1px solid black;
  align-items: center;
  margin-right: 5px;
}

.time {
  color: white;
  width: 80px;
  margin-left: 3px;
  margin-bottom: 1.5px;
}

.time time {
  font-size: 10px;
  letter-spacing: 1px;
}

.time span {
  font-size: 10px;
  letter-spacing: 1px;
}

.sound-c {
  width: 46px;
  height: 25px;
  border: 1px solid #50a6e1;
  border-radius: 5px;
  margin-left: 10px;
  display: flex;
  display: flex;
  align-items: center;
  justify-content: center;
}

.s-bar {
  background: white;
  height: 8px;
  width: 3px;
  margin-right: 2px;
  opacity: 1;
  cursor: pointer;
}

.sound-c span {
  width: 100%;
  height: 100%;
  //background:black;
  display: block;
}

.ex {
  margin-right: 0px;
}

/* .sa {
  width: 80px;
  height: 23px;
  color: black;
  font-weight: bold;
  font-size: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  margin-left: 210px;
  margin-top: 10px;
  transition: 0.3s ease;
  cursor: pointer;
} 

.ba {
  width: 80px;
  height: 23px;
  color: #3399cc;
  font-weight: bold;
  font-size: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  margin-left: 212px;
  margin-top: 8px;
  transition: 0.3s ease;
  cursor: pointer;
}
*/

.videos {
  display: block;
  width: 100%;
  height: auto;
  min-height: 50vh;
  background: #212f3b;
  padding: 8px 5px 10px 5px;
  margin: 5px 0px 0px 0px;
}

.videos.big-videos {
  display: block;
  width: 100vw;
  height: 100vh;
}

.videos h {
  font-size: 18px;
  color: #fff;
  display: block;
  margin: -2px 0px 8px 5px;
}

.video {
  display: block;
  width: 100%;
  height: 80vh;
  overflow: hidden;
  position: relative;
}

.video video {
  width: 100%;
  height: 192.5px;
  position: relative;
  cursor: pointer;
  background: #000;
  overflow: hidden;
  display: block;
}

/* .anim {
  font-size: 55px;
  position: absolute;
  left: 50%;
  top: 40%;
  color: #a5a6a8a3;
  transform: translate(-50%, -50%);
  cursor: pointer;
  display: block;
  width: 100%;
  height: 100%;
} */

.controls {
  display: flex;
  align-items: center;
  width: -webkit-fill-available;
  height: 39px;
  background: linear-gradient(to bottom, #579cce, #3073b3);
  padding: 0px 4px 0px 5px;
  margin-top: -2px;
  position: absolute;
  z-index: 2147483647;
  bottom: 2px;
  transition: 0s;
}

video::-webkit-media-controls-enclosure {
  display: none !important;
}

.controls .playpause {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 53px;
  height: 25px;
  background: linear-gradient(to bottom, #3885bf, #3c8bc3, #2a71b1);
  border: 1.5px solid #50a6e1;
  border-radius: 7px;
  box-shadow: 0px 0px 2px #656565;
}

.playpause span {
  width: 26.5px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  height: 100%;
  margin-top:17px;
}

/* .playpause span:last-child {
  border-left: 1.5px solid #50a6e1;
} */

.playpause i {
  display: block;
  filter: drop-shadow(1px 1px 1px #000);
  color: #fff;
  font-size: 9px;
}

.controls .range {
  display: flex;
  /* width: 64px; */
  width: inherit;
  width: -webkit-fill-available !important;
  height: 10px;
  background: #2d72b2;
  margin: 0px 15px;
  border-width: 1px 1px 2px 1px;
  border-style: solid;
  border-color: #4794cc;
  box-shadow: 0px 0px 5px #0006;
  align-items: center;
  padding: 0px 2px;
  cursor: pointer;
}

.track {
  width: 100%;
  background: #fff;
  display: block;
  height: 3px;
  cursor: pointer;
}

.controls .range .progress-filled {
  width: 0%;
  background: black;
  display: block;
  height: 3px;
  cursor: pointer;
}

.controls .time {
  display: flex;
  font-size: 10px;
  color: #fff;
  letter-spacing: 2px;
}

.controls .volume {
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(to bottom, #3885bf, #3c8bc3, #2a71b1);
  border: 1.5px solid #50a6e1;
  border-radius: 7px;
  box-shadow: 0px 0px 2px #656565;
  width: 50px;
  height: 25px;
  margin-left: 20px;
  cursor: pointer;
}

.controls .volume div {
  width: 5px;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  cursor: pointer;
}

.controls .volume div span {
  width: 3px;
  display: block;
  height: 8px;
  margin-right: 2px;
  background: #fff;
  cursor: pointer;
}

.volume span:last-child {
  margin: 0;
}
.fullscreen_wrapper{
    display: flex;
    font-size: 10px;
    color: #fff;
    letter-spacing: 2px;
}
#fullScreen {
      /* font-size: 20px; */
    /* position: absolute; */
    /* bottom: 44px; */
    color: #fff;
    /* border: 1px solid white; */
    cursor: pointer;
    /* background-color: rgba(0,0,0,0.6); */
    width: 30px;
    border: 1.5px solid #50a6e1;
    border-radius: 7px;
    box-shadow: 0px 0px 2px #656565;
    height: 25px;
    margin-left: 8%;
    display: flex;
    /* padding: 6px; */
    /* transform: translateX(110%); */
    transition: 0.5s;
    align-content: center;
    justify-content: center;
    align-items: center;
}

.sm.videos .video:hover #fullScreen {
  transition: 0.2s !important;
  transform: translateX(0%) !important;
}
.overlay-image{
    background-image: url(http://127.0.0.1:8000/uploads/webinar/featured_images/756427485_webinar_featuredImg_webinar.png);
    height: 110%;
    min-height:105vh;
    width: inherit;
    /* margin-top: -50%; */
    position: absolute;
    z-index: 6;
    background-position:center center;
    background-size: cover;
    background-repeat: no-repeat;
    }


</style>

<div class="wrapper ">
    @include('frontend.partials.account_top')
    @include('frontend.partials.guide_bar')

    <div class="container-fluid pt-3">


        <div class="row mt-3">
            <div class="col-12" style="padding:0px;">
        <div class="videos sm">
          <div class="row">
            <div class="col-9"><h>Playing "{{$webinar[0]->title}}"</h></div>
            <div id="WatchCountDiv" class="col-3 p-0 d-none justify-content-end">
              <h6 class="mt-1"><span class="mr-3 mr-md-5 bg-white px-2 pb-0 py-md-2 rounded"><i class="fa fa-eye pr-2"></i> 
              <span id="WatchCount"></span></span></h6>
          
          </div>
          </div>
          
          <div class="video" style="pointer-events:" onmouseover="moving()">    
            <div id="overlay" class="overlay-image" style="background-image:url({{url('uploads/webinar/featured_images/'.$webinar[0]->featuredImg_image)}}"></div>
            <div  id="player"></div>
        
    </span>
              <div class="controls" style="width:100% !important;">
                <div class="playpause">
                  <span class="pause l">
                    <p>&#9724;</p>
                  </span>
                  <span class="play l">
                    <p>&#9658;</p>
                  </span>
                </div>
                <div class="range">
                  <div class="track">
                    <div class="progress-filled"></div>
                  </div>
                </div>
                <div class="time">
                  <span id="currentTime">00:00</span>/<span id="duration">00:00</span>
                </div>
                <div class="volume">
                  <div onclick="callCurrentVolume(1)" onmouseover="hoVer(1)"><span></span></div>
                  <div onclick="callCurrentVolume(2)" onmouseover="hoVer(2)"><span></span></div>
                  <div onclick="callCurrentVolume(3)" onmouseover="hoVer(3)"><span></span></div>
                  <div onclick="callCurrentVolume(4)" onmouseover="hoVer(4)"><span></span></div>
                  <div onclick="callCurrentVolume(5)" onmouseover="hoVer(5)"><span></span></div>
                  <div onclick="callCurrentVolume(6)" onmouseover="hoVer(6)"><span></span></div>
                </div>
                <div class="d-flex" ><span id="fullScreen" onclick="fullhobe()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-fullscreen" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M5.828 10.172a.5.5 0 0 0-.707 0l-4.096 4.096V11.5a.5.5 0 0 0-1 0v3.975a.5.5 0 0 0 .5.5H4.5a.5.5 0 0 0 0-1H1.732l4.096-4.096a.5.5 0 0 0 0-.707zm4.344 0a.5.5 0 0 1 .707 0l4.096 4.096V11.5a.5.5 0 1 1 1 0v3.975a.5.5 0 0 1-.5.5H11.5a.5.5 0 0 1 0-1h2.768l-4.096-4.096a.5.5 0 0 1 0-.707zm0-4.344a.5.5 0 0 0 .707 0l4.096-4.096V4.5a.5.5 0 1 0 1 0V.525a.5.5 0 0 0-.5-.5H11.5a.5.5 0 0 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 0 .707zm-4.344 0a.5.5 0 0 1-.707 0L1.025 1.732V4.5a.5.5 0 0 1-1 0V.525a.5.5 0 0 1 .5-.5H4.5a.5.5 0 0 1 0 1H1.732l4.096 4.096a.5.5 0 0 1 0 .707z"/>
          </svg></span> </div> 
    

    </div>
  </div>
</div>                   
<div class="row d-none" id="webinarEndMsg">
  <div class="col-12 d-flex justify-content-center">
    <h1 class="text-center bg-success p-3 my-3">This webinar has been closed.</h1>
  </div>
</div>
        </div>
    </div>
</div>

    @include('frontend.common.footer')
    @stop
    @section('appJs')
    <script src = "https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script> 
    var webinar ={!! json_encode($webinar) !!};


var tag = document.createElement("script");

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;





function onYouTubeIframeAPIReady() {
  // console.log("Youtube Iframe is ready");
  player = new YT.Player("player", {
    height: "100% !important",
    // pointer-events: "none",
   
    width: "100% !important",
    // videoId: "sj1t3msy8dc",
    videoId: webinar[0].webinar_link,
    

    playerVars: {
      controls: 0,
enablejsapi:1,
iv_load_policy:3,
    //   autoplay: 1,
      rel: 0,
      showinfo: 0,
      egm: 0,
      showsearch: 0,
      modestbranding: 0
    },
    events: {
      onReady: onPlayerReady,
      onStateChange: onPlayerStateChange
    }
  });
}

function onPlayerReady(event) {
  event.target.playVideo();
}

function onPlayerStateChange(event) {}

window.onload = () => {
  if (sessionStorage.length === 0) {
    var currentVolume = 6;
    sessionStorage.setItem("vol", `${currentVolume}`);
    console.log(sessionStorage.getItem("vol"));
  }
  delay();
};
let he = 1;

function delay() {
  if (he === 0) {
    currentTime();
    callCurrentVolume(sessionStorage.getItem("vol"));
  } else {
    he = 0;
    setTimeout(delay, 1000);
  }
}

let btn = document
  .getElementsByClassName("playpause")[0]
  .querySelectorAll("span");
let currentTimeElement = document.querySelector("#currentTime");
let durationTimeElement = document.querySelector("#duration");
let vid = document.querySelector("#player");
// let anim = document.querySelector(".anim");
let video = document.querySelector(".video");
let fullbtn = document.querySelector("#fullScreen");
let progressBar = document.querySelector(".progress-filled");
let progress = document.querySelector(".range");
// let brac = document.querySelectorAll("brac");
let con = document.querySelector(".controls");
let videos = document.querySelector(".videos");
let time = document.querySelector(".time");
let overlay_img = document.querySelector("#overlay");
let webinarEndMsg = document.querySelector("#webinarEndMsg");


// let mv = 1;
let mvCon = 1;
let mvTime;
let perPos = 0;
let one = 0;
let mOn = 1;
// let yes = 0;
let fullScren = 1;

const currentTime = () => {
  let currentMinutes = Math.floor(player.getCurrentTime() / 60);
  let currentSeconds = Math.floor(
    player.getCurrentTime() - currentMinutes * 60
  );
  let durationMinutes = Math.floor(player.getDuration() / 60);
  let durationSeconds = Math.floor(player.getDuration() - durationMinutes * 60);

  currentTimeElement.innerHTML = `${currentMinutes}:${
    currentSeconds < 10 ? "0" + currentSeconds : currentSeconds
  }`;
  durationTimeElement.innerHTML = `${durationMinutes}:${durationSeconds}`;
  if (player.getPlayerState() === 0) {
    btn[1].querySelector("p").innerHTML = "&#9658;";
    // anim.querySelector("#p-p-s").style.opacity = "1";
    // anim.querySelector("#p-p-s").style.transition = ".3s";
    // anim.querySelector("#p-p-s").innerHTML = "&#9658;";
    if (document.fullscreenElement === null) {
      // anim.querySelector("#p-p-s").style.fontSize = "55px";
    } else {
      // anim.querySelector("#p-p-s").style.fontSize = "100px";
    }
  }
};

function loopTime() {
  currentTime();
  const percentage = (player.getCurrentTime() / player.getDuration()) * 100;
  progressBar.style.width = percentage + "%";
  if (
    player.getCurrentTime() != player.getDuration() ||
    player.getCurrentTime() != 2
  ) {
    setTimeout(loopTime, 1000);
  }
}

btn[0].onclick = () => {
  player.pauseVideo();
  overlay_img.classList.add('overlay-image');

  player.seekTo(0, true);
  progressBar.style.width = "0%";
  if (document.fullscreenElement != null) {
    show();
    clearTimeout(mvTime);
  }
  btn[1].querySelector("p").innerHTML = "&#9658;";
  // anim.querySelector("#p-p-s").style.transition = ".3s";
  // anim.querySelector("#p-p-s").innerHTML = "&#9658;";
  if (document.fullscreenElement === null) {
    // anim.querySelector("#p-p-s").style.fontSize = "55px";
  } else {
    // anim.querySelector("#p-p-s").style.fontSize = "100px";
  }
  // anim.querySelector("#p-p-s").style.opacity = "1";
  loopTime();

};

btn[1].onclick = () => {
  playPause();
};

function playPause() {
  loopTime();

  if (player.getPlayerState() != 1) {
    player.playVideo();
    overlay_img.classList.remove('overlay-image');
    if (document.fullscreenElement != null) {
      show();
      mOn = 1;
      startContdown();
    }

    btn[1].querySelector("p").innerHTML = " &#10073; &#10073;";
    // anim.querySelector("#p-p-s").style.transition = ".3s";
    // anim.querySelector("#p-p-s").innerHTML = " &#10073; &#10073;";
    // anim.querySelector("#p-p-s").style.fontSize = "150px";
    // anim.querySelector("#p-p-s").style.opacity = "0";
  } else {
    player.pauseVideo();
    overlay_img.classList.add('overlay-image');

    if (document.fullscreenElement != null) {
      show();
    }
    btn[1].querySelector("p").innerHTML = "&#9658;";
    // anim.querySelector("#p-p-s").style.transition = ".3s";
    // anim.querySelector("#p-p-s").innerHTML = "&#9658;";
    if (fullScren === 1) {
      // anim.querySelector("#p-p-s").style.fontSize = "55px";
    } else {
      // anim.querySelector("#p-p-s").style.fontSize = "100px";
    }
    // anim.querySelector("#p-p-s").style.opacity = "1";
  }
}

progress.addEventListener("click", (e) => {
  const progressTime =
    (e.offsetX / progress.offsetWidth) * player.getDuration();
  player.seekTo(progressTime, true);
  if (player.getPlayerState() === 1) {
    player.playVideo();
    btn[1].querySelector("p").innerHTML = " &#10073; &#10073;";
    // anim.querySelector("#p-p-s").innerHTML = "&#9658;";
    // anim.querySelector("#p-p-s").style.transition = ".3s";
    // anim.querySelector("#p-p-s").style.fontSize = "150px";
    // anim.querySelector("#p-p-s").style.opacity = "0";
  } else if (player.getPlayerState() === -1) {
    player.pauseVideo();
  } else {
    player.pauseVideo();
    btn[1].querySelector("p").innerHTML = "&#9658;";
    // anim.querySelector("#p-p-s").innerHTML = "&#9658;";
    // anim.querySelector("#p-p-s").style.transition = ".3s";
    if (document.fullscreenElement === null) {
      anim.querySelector("span").style.fontSize = "55px";
    } else {
      // anim.querySelector("#p-p-s").style.fontSize = "100px";
    }
    // anim.querySelector("#p-p-s").style.opacity = "1";
  }
});

let volumeBar = document
  .getElementsByClassName("volume")[0]
  .querySelectorAll("div");
const volArr = [];
var currentVolume = 0;
let volValue = 100 / volumeBar.length;
const minVol = volValue;
for (var i = 0; i < volumeBar.length; i++) {
  volArr[i] = volValue;
  if (i === volumeBar.length - 2) {
    volValue = 100;
  } else {
    volValue += minVol;
  }
}

function callCurrentVolume(n) {
  currentVolume = n;
  sessionStorage.setItem("vol", `${currentVolume}`);
  colorIng();
}

function colorIng() {
  for (var i = 0; i < volumeBar.length; i++) {
    volumeBar[i].querySelector("span").style.background = "white";
  }
  for (var j = 0; j < currentVolume; j++) {
    volumeBar[j].querySelector("span").style.background = "black";
    player.setVolume(volArr[j]);
  }
}

function hoVer(n) {
  for (var i = 0; i < volumeBar.length; i++) {
    volumeBar[i].querySelector("span").style.background = "white";
  }
  for (var j = 0; j < n; j++) {
    volumeBar[j].querySelector("span").style.background = "black";
    player.setVolume(volArr[j]);
  }
  document.querySelector(".volume").onmouseout = () => {
    colorIng();
  };
}

function moving() {
  if (document.fullscreenElement != null) {
    show();
    clearTimeout(mvTime);
    mOn = 1;
    startContdown();
  }
}

function startContdown() {
  mvCon = 1;
  mvConZero();
}

function fullhobe() {
  if (one === 0) {
    perPos = window.scrollY;
    one = 1;
  }

  if (fullScren === 1) {
    full();
  } else {
    small();
  }
}

function full() {
  video.requestFullscreen();
  document.querySelector("iframe").style.width = "100%";
  document.querySelector("iframe").style.height = "95%";
  // document.querySelector("iframe").style.pointer-events = "none";

  con.style.width = "100%";
  con.style.marginLeft = "0%";
  con.style.height = "50px";
  con.style.bottom = "0%";
  con.style.justifyContent = "space-around";
  time.style.fontSize = "13px";
  currentTimeElement.style.fontSize = "13px";
  durationTimeElement.style.fontSize = "13px";
  progress.style.width = "88.5%";
  progress.style.height = "20px";
  fullbtn.style.bottom = "55px";
  // fullbtn.style.transform = "translateX(0%)";
  // brac[0].querySelector("img").src =
  //   "https://cdn-icons.flaticon.com/png/512/5345/premium/5345269.png?token=exp=1653469400~hmac=eb10aafb9e3ae05c3bec050dabe34b29";
  // anim.querySelector("span").style.fontSize = "100px";
  // anim.style.top = "45%";
  fullbtn.style.transition = ".2s";
  videos.classList.remove("sm");
  fullScren = 0;
}

function small() {
  one = 0;
  document.exitFullscreen();
  // console.log(perPos + "exit");
  document.querySelector("iframe").style.height = "78vh";
  // progress.style.width = "64px";
  progress.style.height = "10px";
  con.style.transition = "0s";
  con.style.width = "100%"
  con.style.height = "39px";
  con.style.marginLeft = "0%";
  con.style.bottom = "2px";
  time.style.fontSize = "10px";
  currentTimeElement.style.fontSize = "10px";
  durationTimeElement.style.fontSize = "10px";
  // fullbtn.style.bottom = "44px";
  // fullbtn.style.transform = "translateX(110%)";
  // anim.querySelector("span").style.fontSize = "55px";
  // brac[0].querySelector("img").src =
  //   "https://cdn-icons-png.flaticon.com/512/6907/6907802.png";
  // brac[0].style.transition = "1s";
  // fullbtn.style.transition = ".2s";
  // anim.style.top = "40%";
  videos.classList.add("sm");
  fullScren = 1;
  document.addEventListener("fullscreenchange", function () {
    window.scrollTo(0, perPos);
    // console.log(perPos + "exit");
  });
}

function fullchange() {
  if (document.fullscreenElement === null) {
    small();
  }
}

function hide() {
  fullbtn.style.transition = ".2s";
}

function show() {
  fullbtn.style.transition = ".2s";
}

function mvConZero() {
  if (mvCon === 0) {
    if (!vid.paused) {
      hide();
    } else {
      show();
    }
  } else {
    mvCon = 0;
    mvTime = setTimeout(mvConZero, 3000);
  }
}

// let sbb = document.querySelector("#sbb");

document.addEventListener("fullscreenchange", () => {
  if (document.fullscreenElement) {
    // console.log('Fullscreen');
    full();
    var timeout;
    var duration = 3000;
    document.addEventListener("mousemove", function () {
      con.style.opacity = "1";
      // sbb.style.opacity = "1";
      clearTimeout(timeout);
      timeout = setTimeout(function () {
        con.style.opacity = "0";
        // sbb.style.opacity = "0";
      }, duration);
    });
  } else {
    //console.log('Normal');
    small();
    var timeout;
    var duration = 3000;
    document.addEventListener("mousemove", function () {
      con.style.opacity = "1";
      // sbb.style.opacity = "1";
      clearTimeout(timeout);
      timeout = setTimeout(function () {
        con.style.opacity = "1";
        // sbb.style.opacity = "1";
      }, duration);
    });
  }
});

var videoEndedStatus = setInterval(function() {

var videoState = player.getPlayerState();
if (videoState==0){
overlay_img.classList.add('overlay-image');
document.getElementById('webinarEndMsg').classList.remove('d-none');
  // clearInterval(videoEndedStatus);
}


}, 1000);


// Identifiying who is watching this page
var base_url = window.location.origin;
let web_id = webinar[0].id;
// alert(web_id);
// window.onbeforeunload = function(){
//   active_users();
//   // undifined remove prompts
//   return undefined;
// };

function active_users(){

	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
        dataType:"json",
        url: base_url+'/webinars/de-active-users',
        data: {_token:csrf_token,webinar_id:web_id},
        success: function(data) {
          // alert(data);
	
        },
        error: function(error){
          // alert('error');
    // alert(JSON.stringify(error));

        }
       
    });
 


}
var watchingCountCounter = setInterval(function() {
  // document.getElementById('countbtn').click();
  // active_users();
  watchingCount();

// If the count down is over, write some text 
// if (webinar[0].status == 1) {
// clearInterval(watchingCountCounter);
// }

}, 5000);

function watchingCount(){
var csrf_token = $('meta[name="csrf-token"]').attr('content');
 $.ajax({
      type: "POST",
      dateType: "json",
      url: base_url+'/webinars/watching-count',
      data: {_token:csrf_token,webinar_id:web_id},
      success: function(data) {
        var count= data;
        if (count==0){
          $('#WatchCountDiv').removeClass('d-flex');
          $('#WatchCountDiv').addClass('d-none');
        }
        else{
          $('#WatchCountDiv').addClass('d-flex');
          $('#WatchCountDiv').removeClass('d-none');
          $('#WatchCount').html(data);
        }
      //  alert(data);
      

      },
      error: function(error) {
    // alert(JSON.stringify(error));
    // alert('error');
 }
     
  });



}

</script>

    @stop

