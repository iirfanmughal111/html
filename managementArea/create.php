<?php
include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $title = clean_input($_POST["title"]);
        $artist = clean_input($_POST["artist"]);
        $seller = clean_input($_POST["seller"]);
        $cost = clean_input($_POST["cost"]);
        $notes = mysqli_real_escape_string($conn, $_POST["notes"]);
        $date = clean_input($_POST["date"]);
        echo $date;
        $type = clean_input($_POST["type"]);
        $sell = clean_input($_POST["sell"]);

        $sql = "INSERT INTO `movieDetails` (title, artist, _type, seller, notes, cost, sell, _date) VALUES ('$title', '$artist', '$type','$seller', '$notes', '$cost', '$sell','$date')";

        if ($conn->query($sql)) {
            echo "data inserted";
            header("location:index.php");
        } else {
            mysqli_connect_errno();
            $conn->error;

            echo 'query problem';
        }
    }
}

function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>



<div class="container">
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <a href="index.php" class="btn btn-primary">Home</a>
        </div>
    </div>
    <div class="row d-flex justify-content-center">

        <div class="col-lg-8 ms-3">
            <form method="post" action="<?php $_SERVER["PHP_SELF"] ?>">

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input required type="text" class="form-control" name="title" aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="artist" class="form-label">Artist</label>
                    <input required type="text" class="form-control" name="artist" aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="seller" class="form-label">Seller</label>
                    <input required type="text" class="form-control" name="seller" aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="cost" class="form-label">Cost</label>
                    <input required type="number" class="form-control" name="cost" aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <input required type="text" class="form-control" name="notes" aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input required type="date" class="form-control" name="date" aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input required type="text" class="form-control" name="type" aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="sell" class="form-label">Sell</label>
                    <input required type="number" class="form-control" name="sell" aria-describedby="sell">
                </div>

                <button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>

            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>