<?php
include 'header.php';

$id = $_GET['viewId'];
if (isset($id)) {
    $sql = "SELECT * FROM movieDetails WHERE id=$id";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $title = clean_input($_POST["title"]);
        $artist = clean_input($_POST["artist"]);
        $seller = clean_input($_POST["seller"]);
        $cost = clean_input($_POST["cost"]);
        $notes = clean_input($_POST["notes"]);
        $date = clean_input($_POST["date"]);

        $type = clean_input($_POST["type"]);
        $sell = clean_input($_POST["sell"]);
        $sql = "UPDATE  `movieDetails` SET title = '$title',artist = '$artist',_type = '$type',seller = '$seller',notes = '$notes',cost = '$cost',sell = '$sell',_date = '$date' WHERE id='$id'";



        if ($conn->query($sql)) {

            header("location :index.php");
        } else {
            mysqli_connect_errno();
            $conn->error;

            echo 'query problem -> ' . $sql;
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
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-danger btn-sm" data-bs-data="<?php echo $row['title']; ?>"
                data-bs-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#deleteConfiramtionModal">
                Delete
            </button>
            <a href="index.php" class="btn btn-primary ms-2">Home</a>
        </div>
    </div>
    <div class="row d-flex justify-content-center">

        <div class="col-lg-6">
            <div class="row ms-3">
                <div class="row rounded border mb-4 p-2">
                <div class="col-12">
                    <span><strong class="me-4 ">Title : </strong></span>
                </div>
                <div class="col-10  offset-2 mb-3">
                    <?php echo $row['title']; ?>
                </div>
                </div>

                <div class="row rounded border mb-4 p-2">
                <div class="col-12">
             <strong class="me-4">Artist : </strong>
                </div>
                <div class="col-10 offset-2 mb-3">
              <?php echo $row['artist']; ?>
                </div>
                </div>

                <div class="row rounded border mb-4 p-2">
                 <div class="col-12">
                 <strong class="me-4">Seller : </strong>
                 </div>
                 <div class="col-10 offset-2 mb-3">
                 <span><?php echo $row['seller']; ?></span>
                 </div>
                 </div>

                 <div class="row rounded border mb-4 p-2">
                <div class="col-12">
                <strong class="me-4">Cost : </strong>
                </div>
                <div class="col-10 offset-2 mb-3">
                <span><?php echo $row['cost']; ?></span>
                </div>
                </div>

                <div class="row rounded border mb-4 p-2">
               <div class="col-12">
               <strong class="me-4">Notes : </strong>
               </div>
               <div class="col-10 offset-2 mb-3">
               <span><?php echo $row['notes']; ?></span>
               </div>
               </div>

               <div class="row rounded border mb-4 p-2">
               <div class="col-12">
               <strong class="me-4">Date : </strong>
               </div>
                <div class="col-10 offset-2 mb-3">
                <span><?php echo $row['_date']; ?></span>
                </div>
                </div>

                <div class="row rounded border mb-4 p-2">
                <div class="col-12">
                <strong class="me-4">Type : </strong>
                </div>
                <div class="col-10 offset-2 mb-3">
                <span><?php echo $row['_type']; ?></span>
                </div>
                </div>

                <div class="row rounded border mb-4 p-2">
                <div class="col-12">
                <strong class="me-4">Sell : </strong>
                </div>
                <div class="col-10 offset-2 mb-3">
                <span><?php if ($row['sell'] == 1) echo 'YES';
                                                            else echo 'No'; ?></span>
                </div>
                </div>
           


                <p></p>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteConfiramtionModal" tabindex="-1" aria-labelledby="deleteConfiramtionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title text-danger bg-white p-2 rounded" id="deleteConfiramtionModalLabel">Confirm action
                </h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please confirm that you want to delete the following: </p>
                <h5 class="text-danger">Are You Sure to Delete "<?php echo $row['title'] ?>" ?</h5>
            </div>
            <div class="modal-footer bg-secondary">
                <button type="button" class="btn btn-secondary bg-white text-dark btn-sm"
                    data-bs-dismiss="modal">Close</button>
                <a href="delete.php?deleteId=<?php echo $id; ?>" class='btn btn-sm btn-danger'>Delete</a>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>

