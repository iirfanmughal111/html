<?php 
include 'header.php';

$id = $_GET['updateId'];
if (isset($id)){
    $sql = "SELECT * FROM movieDetails WHERE id=$id";

    $result = $conn->query($sql);
     $row = $result->fetch_assoc();
    
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['submit'])){
      $title = clean_input($_POST["title"]);
      $artist = clean_input($_POST["artist"]);
      $seller = clean_input($_POST["seller"]);
      $cost = clean_input($_POST["cost"]);
      $notes = clean_input($_POST["notes"]);
      $date = clean_input($_POST["date"]);

      $type = clean_input($_POST["type"]);
      $sell = clean_input($_POST["sell"]);
    $sql = "UPDATE  `movieDetails` SET title = '$title',artist = '$artist',_type = '$type',seller = '$seller',notes = '$notes',cost = '$cost',sell = '$sell',_date = '$date' WHERE id='$id'";



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
            <a href="index.php" class="btn ms-3 btn-primary">Home</a>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        
        <div class="col-lg-8">
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
                    <input type="date" value="<?php echo date("Y-m-d", strtotime($row['_date'])); ?>" class="form-control" name="date"
                        aria-describedby="date">
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" value="<?php echo $row['_type']; ?>" class="form-control" name="type"
                        aria-describedby="date">
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Sell</label>
                    <input type="number" value="<?php echo $row['sell']; ?>" class="form-control" name="sell"
                        aria-describedby="date">
                </div>

                <button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>

            </form>
        </div>
    </div>
</div>


<?php include 'footer.php';?>
