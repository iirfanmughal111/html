<?php
// FROM HASH: 704380de570a13a7e18cf0db444b56f1
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped(' ' . 'Withdraw Money' . ' ');
	$__finalCompiled .= '
';
	$__templater->wrapTemplate('account_wrapper', $__vars);
	$__finalCompiled .= '
';
	$__templater->includeCss('fs_escrow_otp.less');
	$__finalCompiled .= '

' . $__templater->form('
  <div class="block-container">
    <div class="block-body">
    <input type="hidden" name="full_otp" id="full_otp">
    <input type="hidden" value="' . $__templater->escape($__vars['data']['destination_address']) . '" name="destination_address">
    <input type="hidden" value="' . $__templater->escape($__vars['data']['withdraw_amount']) . '"name="withdraw_amount" >
		<div class="title">
    <h3>' . 'OTP VERIFICATION' . '</h3>
    <p class="info">' . 'An OTP has been sent to ' . $__templater->escape($__vars['xf']['visitor']['email']) . '.' . ' </p>
    <p class="msg">' . 'Please enter OTP to verify and submit the request.' . ' </p>
  </div>
  <div class="otp-input-fields">
    <input type="number" class="otp__digit otp__field__1">
    <input type="number" class="otp__digit otp__field__2">
    <input type="number" class="otp__digit otp__field__3">
    <input type="number" class="otp__digit otp__field__4">
    <input type="number" class="otp__digit otp__field__5">
    <input type="number" class="otp__digit otp__field__6">
	  
	<button type="submit" class="button--primary button">Request</button>
  </div>
  
  <div class="result"><p id="_otp" class="_notok">000000</p></div>
  </div>
	  </div>
	  
', array(
		'action' => $__templater->func('link', array('escrow/withdraw-save', ), false),
		'ajax' => 'false',
		'data-force-flash-message' => 'true',
	)) . '

<script>
	var otp_inputs = document.querySelectorAll(".otp__digit")
var mykey = "0123456789".split("")
otp_inputs.forEach((_)=>{
  _.addEventListener("keyup", handle_next_input)
})
function handle_next_input(event){
  let current = event.target
  let index = parseInt(current.classList[1].split("__")[2])
  current.value = event.key
  
  if(event.keyCode == 8 && index > 1){
    current.previousElementSibling.focus()
  }
  if(index < 6 && mykey.indexOf(""+event.key+"") != -1){
    var next = current.nextElementSibling;
    next.focus()
  }
  var _finalKey = ""
  for(let {value} of otp_inputs){
      _finalKey += value
  }
  if(_finalKey.length == 6){
    document.querySelector("#_otp").classList.replace("_notok", "_ok")
    document.querySelector("#_otp").innerText = _finalKey
    document.querySelector("#full_otp").value = _finalKey
	  
  }else{
    document.querySelector("#_otp").classList.replace("_ok", "_notok")
    document.querySelector("#_otp").innerText = _finalKey
    document.querySelector("#full_otp").value = _finalKey
	  
  }
}
</script>';
	return $__finalCompiled;
}
);