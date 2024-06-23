<?php include 'connection.php'; ?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./style.css">

    <title>Management Area</title>
</head>

<body>
    <div class="container">
        <div class="row rounded mt-5 mb-3 bg-dark text-white">
            <div class="col-12">
                <h1 class="text-center py-3 ">Management Area</h1>
            </div>
        </div>
    </div>
    <?php
    if ($conn) {
        if ($conn->query("DESCRIBE movieDetails")) {
        } else {
            $sql = "CREATE TABLE movieDetails (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(130) DEFAULT NULL,
            artist VARCHAR(130) DEFAULT NULL,
            _type VARCHAR(130) DEFAULT NULL,
            seller VARCHAR(130) DEFAULT NULL,
            notes VARCHAR(230) DEFAULT NULL,
            cost Int(13) DEFAULT NULL,
            sell Int(13) DEFAULT NULL,
            _date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            if ($conn->query($sql) === TRUE) {
                echo "Table movieDetails created successfully";
            } else {
                echo "Error creating table: " . $conn->error;
            }
        }
    } else {
        echo 'connection problem';
    }
    ?>