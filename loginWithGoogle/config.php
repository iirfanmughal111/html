<?php
//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

$clientId = '162625916317-ttsu0tidmbjelo1tcgpf3evn37ftepk5.apps.googleusercontent.com';
$clientSecert = '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';
$redirectURL = 'http://localhost/loginWithGoogle/index.php';


//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId($clientId );

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret($clientSecert);

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri($redirectURL);

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page
session_start();


//Connecting to Db

$servername = "localhost";
$username = "root";
$password = "admin123";
$dbname = "googleLogin";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($conn) {
    if ($conn->query("DESCRIBE users")) {
    } else {
        $sql = "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        access_token VARCHAR(130) DEFAULT NULL,
        email VARCHAR(130) DEFAULT NULL,
        gender VARCHAR(130) DEFAULT NULL,

        _date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table users created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
        }
    }
} else {
    echo 'connection problem';
}

?>