function _(el) {
  return document.getElementById(el);
}

$(document).ready(function () {
  // Do your event binding in JavaScript, not as inline HTML event attributes:
  $("#bunnyVideoBtn").on("click", set_bunny_val);
  function set_bunny_val() {
    $('input[name="isBunnyUpload"]').val("1");
  }
  $("#attachVideoBtn").on("click", set_attach_val);
  function set_attach_val() {
    $('input[name="isBunnyUpload"]').val("0");
  }
});
