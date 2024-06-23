<?php 
include 'header.php';
//  error_reporting(E_ALL);
//   ini_set('display_errors', '1');

$id = $_GET['updateId'];
if (isset($id)){
    $sql = "SELECT * FROM movieDetails WHERE id=$id";

    $result = $conn->query($sql);
     $row = $result->fetch_assoc();
    
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['submit'])){
      $title = clean_input($_POST["title"]);
     // echo $title;
      $artist = clean_input($_POST["artist"]);
    //  echo $artist;
      $seller = clean_input($_POST["seller"]);
    //  echo $seller;
      $cost = clean_input($_POST["cost"]);
    //  echo $cost;
      $notes = clean_input($_POST["notes"]);
    //  echo $notes;
      $date = clean_input($_POST["date"]);

      $type = clean_input($_POST["type"]);
    //  echo $type;
      $sell = clean_input($_POST["sell"]);
    //  echo $sell;
if (isset($id)){
    echo 'click';
    $sql = "UPDATE  `movieDetails` SET title = '$title',artist = '$artist',_type = '$type',seller = '$seller',notes = '$notes',cost = '$cost',sell = '$sell',_date = '$date' WHERE id='$id'";


}
else{
    $sql = "INSERT INTO `movieDetails` (title', artist, _type, seller, notes, cost, sell, _date) VALUES ('$title', '$artist', '$type','$seller', '$notes', '$cost', '$sell','$date')";
}
//     $sql = "INSERT INTO movieDetails ('title', 'artist', '_type', 'seller, `notes`, `cost`,sell, _date) VALUES ('title', 'artist', 'type','seller', 'notes', 'cost', 'sell','date')";


    //$result = $conn->query($sql);
     if ($conn->query($sql)){

         header("location:index.php");
     }else{
        mysqli_connect_errno();
        $conn->error;
       
        echo 'query problem -> '.$sql;
     }
      
    }
}

function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>



<div class="container">
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <a href="index.php" class="btn btn-primary">Home Page</a>
        </div>
        <div class="col-12">
            <form method="post" action="<?php $_SERVER["PHP_SELF"]?>">

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" value=" <?php echo $row['title']; ?>" class="form-control" name="title"
                        aria-describedby="title">
                </div>
                <div class="mb-3">
                    <label for="artist" class="form-label">Artist</label>
                    <input type="text" value="<?php echo $row['artist']; ?>" class="form-control" name="artist"
                        aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="seller" class="form-label">Seller</label>
                    <input type="text" value="<?php echo $row['seller']; ?>" class="form-control" name="seller"
                        aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="cost" class="form-label">Cost</label>
                    <input type="number" value="<?php echo $row['cost']; ?>" class="form-control" name="cost"
                        aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <input type="text" value="<?php echo $row['notes']; ?>" class="form-control" name="notes"
                        aria-describedby="tile">
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" value="<?php echo $row['_date']; ?>" class="form-control" name="date"
                        aria-describedby="date">
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" value="<?php echo $row['_type']; ?>" class="form-control" name="type"
                        aria-describedby="date">
                </div>

                <label for="type" class="form-label">Sell</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sell" id="sell" value=1
                        <?php if(isset($id) && $row['sell']==1) echo 'checked' ?>>
                    <label class="form-check-label" for="sell">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sell" id="sell" value=0
                        <?php if(isset($id) && $row['sell']==0) echo 'checked' ?>>
                    <label class="form-check-label" for="sell">
                        No
                    </label>
                </div>


                <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>

            </form>
        </div>
    </div>
</div>

<?php include 'footer.php';?>