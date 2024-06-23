<?php
include 'connection.php';
$id = $_GET['deleteId'];

    $query = "delete from `movieDetails` where id =$id";
    $result = mysqli_query($conn, $query);

    if ($result){
        header('location:index.php');
    }else{
        echo 'query problem';
    }

    

?>