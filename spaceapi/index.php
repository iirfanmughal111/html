<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;

$main_key = 'dop_v1_bca3f6b6e80db48a9326d53afd2e8ca40288d486a01932fe0ea58b332b39b369';
$space_key = 'KJRCDY7LL4EWSUOPIE5J';
$secert_key = 'BkqNnQtTlUXggckvB+1MU/wHCd/U1DiaGXtOO7ShchA';
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$s3 = new S3Client([
    'credentials' => [
        'key' => $space_key,
        'secret' => $secert_key,
        
    ],
    'endpoint' => 'https://ams3.digitaloceanspaces.com',
    'region' => 'ams3',
    'version' => 'latest'
    
]);



try {
    $result = $s3->getObject(array(
        'Bucket' => 'e-dewan',
        'Key'    => '/data/BunnyIntegration/testbunmp4'
    ));
    var_dump($result);
} catch (Exception $e) {
    var_dump('issue');
   // I can put a nicer error message here  
}


$object = $s3->getObject([
    'Bucket' => 'e-dewan',
    'Key' =>  '/data/BunnyIntegration/testbun.mp4',
]);
$objectsListResponse = $s3->listObjects(['Bucket' => "e-dewan"]);
var_dump($object);

$contents = isset($object['Body']) ?  $object['Body'] : NULL;

// Do something with the contents
echo $contents;

$s3->close();




// $s3 = new Aws\S3\S3Client([
//     'region' => 'ams3',
//     'version' => 'latest',
//     'credentials' => [
//         'key' => $space_key,
//         'secret' => $secert_key,
//     ],
// ]);

// $object = $s3->getObject([
//     'Bucket' => 'e-dewan',
//     'Key' => 'data/BunnyIntegration',
// ]);

// $contents = $object['Body'];

// // Do something with the contents
// echo $contents;

// $s3->close();