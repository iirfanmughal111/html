<?php 
include 'header.php';
require_once 'vendor/autoload.php';
$clientId = '162625916317-ttsu0tidmbjelo1tcgpf3evn37ftepk5.apps.googleusercontent.com';
$clientSecert = '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';
$redirectURL = 'http://localhost/GoogleLogin/login.php';


if(!session_id()){
    session_start();
    echo '<div class="alert alert-danger alert>sdff</div>';
}
unset($_SESSION['token']);
$client->revokeToken();
session_destroy();
// session_destroy();
//Creating client request to Google Login
$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecert);
$client->setRedirectUri($redirectURL);
//$client->setScopes(Google_Service_Slides::PRESENTATIONS_READONLY);
$client->setScopes('profile');
$client->setScopes('email');
$client->setApprovalPrompt('force');
if (isset($_GET['code'])) {
    echo '<div class="alert alert-danger alert>code</div>';

    $_SESSION['token'] = $gClient->getAccessToken(); 
    header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL)); 
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
}
if(isset($_SESSION['token'])){ 
$client->setAccessToken($token);
echo '<div class="alert alert-danger alert>session</div>';

}
if($gClient->getAccessToken()){ 
// Getting User Profile
$googleAuth = new Google_Service_Oauth2($client);
$googleUser = $googleAuth->userinfo->get();
$email = $googleUser->email;
// var_dump($googleUser);exit;
$name = $googleUser->last_name;
echo "$name -> $email";
echo '<div class="alert alert-danger alert>data</div>';

}
else{
    echo '<div class="alert alert-danger alert>else</div>';

    echo "<a class='btn btn-primamry' href='".$client->createAuthUrl()."' > Login with Google</a>";
}

?>