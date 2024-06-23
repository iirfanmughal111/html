<?php session_start();
include 'header.php';
function logout(){
    echo 'fun called';
    $client->revokeToken();
    header("Location:login.php");
}
$loginText ='';
$satus = 0;
$logutURL = 'logout.php';
//Creating client request to Google Login
$client = new Google_Client();
$_SESSION["client"] =$client;
$client->setClientId($clientId);
$client->setClientSecret($clientSecert);
    $client->revokeToken();
    $client->setRedirectUri($redirectURL);
//$client->setScopes(Google_Service_Slides::PRESENTATIONS_READONLY);
$client->setScopes('profile');
$client->setScopes('email');
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->setApprovalPrompt('force');
if (isset($_GET['code'])) {
    
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
$_SESSION["token"] = $token ;


$client->setAccessToken($token);
// Getting User Profile
$googleAuth = new Google_Service_Oauth2($client);
$googleUser = $googleAuth->userinfo->get();
$email = $googleUser->email;
$fullName = $googleUser->givenName;
$firstName = $googleUser->name;

$imageURL = $googleUser->picture;
$gender = $googleUser->gender;
$emailVerfied = $googleUser->verifiedEmail;

$userId = $googleUser->id;

$gender = $googleUser->gender;



$satus = 1;

// $client->revokeToken();
$loginText = "<h6>Your name is $name , $name2, your email is $email and you are successfully login. Your gender is $gender. Your email verfied stats is $emailVerfied. Your google user id is $userId.</h6><br><p><a class='btn btn-primary' href='".$logutURL."' > Logout</a></p> <img src='".$imageURL."'>  <form method='post'>
        <input type='submit' name='logout'
                class='logout' value='logout' />
       
    </form>";
    $loginText ='';
}else{
    
    $loginText = "<a class='btn btn-primary' href='".$client->createAuthUrl()."' > Login with Google</a>";
}
?>


    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-6  my-5 p-5 border">
                <p>Your First name is:   <?php echo $firstName;?> </p>
                <p>Your Full name is:   <?php echo $fullName;?> </p>
                <p>Your email is:   <?php echo $email;?> </p>
                <p>Your Google User Id  is:   <?php echo $userId;?> </p>
                <p>Your email verified status is:   <?php echo $emailVerfied;?> </p>
                <p>Your session token is:   <?php echo $_SESSION["token"]['access_token'];?> </p>

               <?php var_dump($_SESSION["token"]);?>

</div>

<div class="col-4  my-5 p-5 border">
    <?php 

    if( $loginText ){
     echo   $loginText;

    }
    else{
        echo   "<img src='".$imageURL."'>";

    }
    
    ?>



</div>

          
         
            
        </div>
    </div>
    
    <?php 
    
    ?>
