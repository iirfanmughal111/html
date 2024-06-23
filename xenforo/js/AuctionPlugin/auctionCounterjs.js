//var selectedWebinar ={!! json_encode($selectedWebinar) !!};
//var dateTime = selectedWebinar.start_datetime;

var dateTime = 1683684380;
function dateConverter(dateTime) {
  var humanDate = new Date(dateTime * 1000);
  var year = humanDate.getFullYear();
  var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
  var date = humanDate.getDate();
  var fulldate = year + "-" + month + "-" + date;

  return fulldate;
}

function timeConverter(dateTime) {
  var wStart_time = new Date(dateTime * 1000).toLocaleString("en-GB", {
    hour12: false,
    //timeZone:'Europe/London',
    timeStyle: "short",
  });

  return wStart_time;
}

var selectedStartTime = timeConverter(dateTime);
var selectedStartDate = dateConverter(dateTime);
//var selectedCountDownDate = new Date(selectedStartDate + " " + selectedStartTime + ":00").getTime();

var dateTimeFormating = selectedStartDate + " " + selectedStartTime + ":00";
// FormatingDateEspecialyForIOS
var tempCountTime = dateTimeFormating.split(/[- :]/);
// Apply each element to the Date function
var tempDateObject = new Date(
  tempCountTime[0],
  tempCountTime[1] - 1,
  tempCountTime[2],
  tempCountTime[3],
  tempCountTime[4],
  tempCountTime[5]
);
var selectedCountDownDate = new Date(tempDateObject).getTime();

var selectedCounter = setInterval(function () {
  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = selectedCountDownDate - now;
  document.getElementById("days").innerHTML =
    Math.floor(distance / (1000 * 60 * 60 * 24)) + " D";
  document.getElementById("hours").innerHTML =
    Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) + " H";
  document.getElementById("minutes").innerHTML =
    Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)) + " M";
  document.getElementById("seconds").innerHTML =
    Math.floor((distance % (1000 * 60)) / 1000) + " S";

  // If the count down is over, write some text
  if (distance < 0) {
    clearInterval(selectedCounter);
    var url = window.location.origin + "/webinars/play/" + selectedWebinar.id;
    document.getElementById("days").innerHTML = "<a href=" + url + ">Join</a>";
    document.getElementById("hours").classList.add("d-none");
    document.getElementById("minutes").classList.add("d-none");
    document.getElementById("seconds").classList.add("d-none");
  }
}, 1000);

//For Mulitiple counter

// For All OtherWebinars
function DateTimeConverter(unixdatetime) {
  var wStart_time = new Date(unixdatetime * 1000).toLocaleString("en-GB", {
    hour12: false,
    // timeZone:'Europe/London',
    timeStyle: "short",
  });
  var humanDate = new Date(unixdatetime * 1000);
  var year = humanDate.getFullYear();

  var month = (humanDate.getMonth() + 1).toString().padStart(2, "0");
  var date = humanDate.getDate();

  var fulldate = year + "-" + month + "-" + date + " " + wStart_time + ":00";

  // var dateTimeFormating = selectedStartDate + " " + selectedStartTime + ":00";
  // FormatingDateEspecialyForIOS
  var tempCountTimmer = fulldate.split(/[- :]/);
  // Apply each element to the Date function
  var tempDateObject = new Date(
    tempCountTimmer[0],
    tempCountTimmer[1] - 1,
    tempCountTimmer[2],
    tempCountTimmer[3],
    tempCountTimmer[4],
    tempCountTimmer[5]
  );
  var CountDownDateTime = new Date(tempDateObject).getTime();

  return CountDownDateTime;
}

function timmerCounter(webinar_id, start_datetime) {
  let web_id = webinar_id;
  let humanDateTime = DateTimeConverter(start_datetime);

  var countDownDate = new Date(humanDateTime).getTime();
  var counter = setInterval(function () {
    // Get today's date and time
    var now = new Date().getTime();
    // Find the distance between now and the count down date
    var timeDistance = countDownDate - now;
    document.getElementById("days-auction-" + web_id).innerHTML =
      Math.floor(timeDistance / (1000 * 60 * 60 * 24)) + " D";
    document.getElementById("hours-auction-" + web_id).innerHTML =
      Math.floor((timeDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) +
      " H";
    document.getElementById("minutes-auction-" + web_id).innerHTML =
      Math.floor((timeDistance % (1000 * 60 * 60)) / (1000 * 60)) + " M";
    document.getElementById("seconds-auction-" + web_id).innerHTML =
      Math.floor((timeDistance % (1000 * 60)) / 1000) + " S";

    // If the count down is over, write some text
    if (timeDistance < 0) {
      clearInterval(counter);
      var url = window.location.origin + "/webinars/play/" + web_id;
      document.getElementById("days-" + web_id).innerHTML =
        "<a href=" + url + ">Join</a>";
      document.getElementById("hours-" + web_id).classList.add("d-none");
      document.getElementById("minutes-" + web_id).classList.add("d-none");
      document.getElementById("seconds-" + web_id).classList.add("d-none");
    }
  }, 1000);
}
