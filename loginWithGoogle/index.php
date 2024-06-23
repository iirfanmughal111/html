<?php

//index.php

//Include Configuration File
include('config.php');

$login_button = '';

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if (isset($_GET["code"])) {
    //It will Attempt to exchange a code for an valid authentication token.
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
    if (!isset($token['error'])) {
        //Set the access token used for requests
        $google_client->setAccessToken($token['access_token']);

        //Store "access_token" value in $_SESSION variable for future use.
        $_SESSION['access_token'] = $token['access_token'];
        $accessToken = $token['access_token'];
        //Create Object of Google Service OAuth 2 class
        $google_service = new Google_Service_Oauth2($google_client);

        //Get user profile data from google
        $data = $google_service->userinfo->get();

        //Below you can find Get profile data and store into $_SESSION variable
        if (!empty($data['given_name'])) {
            $_SESSION['username'] = $data['given_name'];
        }

        if (!empty($data['family_name'])) {
            $_SESSION['user_last_name'] = $data['family_name'];
        }

        if (!empty($data['email'])) {
            $email = $data['email'];

            $_SESSION['email'] = $data['email'];
        }

        if (!empty($data['gender'])) {
            $_SESSION['user_gender'] = $data['gender'];
        }

        if (!empty($data['picture'])) {
            $_SESSION['user_image'] = $data['picture'];
        }
    }



    $sqlfetch = "SELECT * FROM users WHERE email='" . $_SESSION['email'] . "'";
    // $sqlfetch = "SELECT * FROM users WHERE email='a@b.com'";


    $result = $conn->query($sqlfetch);
    $row = $result->fetch_assoc();
    $msg = "Login with new Google account";
    if ($row) {
        if ($row['access_token'] == $_SESSION['access_token']) {
            $msg = "Login with existing Google successful";
        }

       
    }

var_dump($data);echo "<br>";
var_dump($_SESSION['access_token']);echo "<br>";


    if ($row['email'] == null) {
        $sql = "INSERT INTO `users` (access_token, email, gender) VALUES ('" . trim($_SESSION['access_token']) . "', '" . trim($_SESSION['email']) . "', '" . trim($_SESSION['user_gender']) . "')";
        $result = $conn->query($sql);
    }




    // if(trim($row['aaccess_token'])==trim($_SESSION['access_token'])){
    //     echo $row['aaccess_token'];
    //     echo '\nlogin successful';
    //     echo $_SESSION['access_token'];

    // }
    // else {
    //     echo $row['aaccess_token'];
    //     echo '<br><br><br>login failed';
    //     echo $_SESSION['access_token'];
    // }
}
// ya29.a0AVvZVsq-E87Zao-vbTpwWbrAX_8geLcWJChBqben0B9Nbls_2gDPPIw83cMIUdWl85wNQNIXU8Qn9VdNJPCbkdpBZNdBQ3zAWBkJy2Layssp4EG2Hpr1NAvF-Lnp4h1SQHMrYR19yQ1YtaJdiHeTlAqL-SEKaCgYKAYsSARASFQGbdwaIFDWvEEJ036wlmeAQW8u0ew0163


// ya29.a0AVvZVspXmub2CH0L09pMw2IzTeTRaW8nLTLJAyVsFi7y2__t8Cb_EJj65SytTAUFJ4lSaottxsltFlFXFXQP0caDKqWhMa1_zuflF_sm8gzVBYfmCjj_yfcv0EySzqw_auk_zpziJXyCp1yF3I7pTZG9wm3FSwaCgYKARMSAQ8SFQGbdwaIHNBq_jEVduhMXEqq0rKfAA0165


//This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
if (!isset($_SESSION['access_token'])) {
    //Create a URL to obtain user authorization
    $login_button = '<a href="' . $google_client->createAuthUrl() . '"><img src="sign-in-with-google.png" /></a>';
}

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login with Google</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body>
    <div class="container">
        <br />
        <h2 align="center">Login with Google</h2>
        <br />
        <div class="panel panel-default">
            <?php
            if ($login_button == '') {
                echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
                echo '<img src="' . $_SESSION["user_image"] . '" class="img-responsive img-circle img-thumbnail" />';
                echo '<h3><b>Name :</b> ' . $_SESSION['username'] . ' ' . $_SESSION['user_last_name'] . '</h3>';
                echo '<h3><b>Email :</b> ' . $_SESSION['email'] . '</h3>';
                echo '<h3><b>Account Status :</b> ' . $msg . '</h3>';
                echo '<h3><a href="logout.php">Logout</h3></div>';
            } else {
                echo '<div align="center" style=" background-color:#f2f2f266;" >' . $login_button . '</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>
