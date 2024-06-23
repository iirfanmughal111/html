<?php include 'header.php';

$limit = 25;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM movieDetails WHERE id IS NOT NULL";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {

        if (!empty($_POST["title"])) {
            $title = clean_input($_POST["title"]);
            $sql .= " AND title LIKE '%$title%'";
        }

        if (!empty($_POST["artist"])) {
            $artist = clean_input($_POST["artist"]);
            $sql .= " AND artist LIKE '%$artist%'";
        }
        if (!empty($_POST["seller"])) {
            $seller = clean_input($_POST["seller"]);
            $sql .= " AND seller LIKE '%$seller%'";
            echo $seller;
        }
        if (!empty($_POST["notes"])) {
            $notes = clean_input($_POST["notes"]);
            $sql .= " AND notes LIKE '%$notes%'";
            echo $notes;
        }

        if (!empty($_POST["type"])) {

            $type = clean_input($_POST["type"]);
            $sql .= " AND type LIKE '%$type%'";
        }
        if (isset($_POST["date"])) {
            $date = clean_input($_POST["date"]);
            $sql .= " ORDER BY _date $date";
        }
    }
} else {
    $sql .= " ORDER BY _date ASC";
}

function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<div id="admin-content" class="container">
    <div class="row ">
        <div class="col-12 d-flex justify-content-end">
            <a href="create.php" class="btn btn-primary">Add new</a>
        </div>

        <div class="col-12 shadow border my-5 p-5">
            <h6>Searching Area</h6>

            <form method="post" action="<?php $_SERVER["PHP_SELF"] ?>">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" value="<?php echo $title; ?>" placeholder="Search by Title" class="form-control" name="title" aria-describedby="title">

                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="artist" class="form-label">Artist</label>
                            <input type="text" value="<?php echo $artist; ?>" placeholder="Search by Artis" class="form-control" name="artist" aria-describedby="tile">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <input type="text" value="<?php echo $notes; ?>" placeholder="Search by Notes" class="form-control" name="notes" aria-describedby="tile">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="seller" class="form-label">Seller</label>
                            <input type="text" value="<?php echo $seller; ?>" placeholder="Search by Seller" class="form-control" name="seller" aria-describedby="title">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" placeholder="Search by Type" class="form-control" value="<?php echo $type; ?>" name="type" aria-describedby="title">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="date" class="form-label">Sort by Date</label>
                            <select name="date" class="form-select" aria-label="Date select ">
                                <option value="ASC" <?php if ($date == 'ASC') {
                                                        echo 'selected';
                                                    }; ?>>Ascending</option>
                                <option value="DESC" <?php if ($date == 'DESC') {
                                                            echo 'selected';
                                                        }; ?>>Descending</option>

                            </select>
                        </div>

                    </div>
                </div>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

    </div>
    <div class="col-12">
        <div class="col-12 d-flex justify-content-end">
            <a id="a2" download="managementArea.csv" class="btn btn-primary mb-3">Export Data</a>
        </div>
        <table class="table table-striped" style="overflow-x:auto">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Artist</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Sell</th>
                    <th scope="col">Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $paginationSql = $sql;
                if ($paginationSql == "SELECT * FROM movieDetails WHERE id IS NOT NULL ORDER BY _date ASC") {
                    $sql .= " LIMIT {$offset},{$limit}";
                }

                $result = mysqli_query($conn, $sql);

                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $index = 1;
                if ($result->num_rows > 0) {
                    foreach ($data as $value) {
                        $id = $value['id'];
                        echo "<tr><th scope='row'>" . $index . "</th>";
                        echo "<td><a class='nav-link' href='view.php?viewId=$id'>" . $value['title'] . "</a></td>";
                        echo "<td>" . $value['artist'] . "</td>";
                        echo "<td>" . $value['seller'] . "</td>";
                        echo "<td>" . $value['cost'] . "</td>";
                        echo "<td>" . substr($value['notes'], 0, 30) . " ...</td>";
                        echo "<td>" . date("Y-m-d", strtotime($value['_date'])) . "</td>";
                        echo "<td>" . $value['_type'] . "</td>";
                        echo "<td>" . $value['sell'] . "</td>";
                        echo "<td><a href='update.php?updateId=$id' class='btn btn-sm btn-primary'>Edit</a></td>";
                        $index++;
                    }
                    echo "</tr>";
                } else {
                    echo "0 results";
                }
                ?>

            </tbody>
        </table>
        <?php

        if ($paginationSql == "SELECT * FROM movieDetails WHERE id IS NOT NULL ORDER BY _date ASC") {
            $result1 = mysqli_query($conn, $paginationSql) or die("Query Failed.");

            if (mysqli_num_rows($result1) > 0) {

                $total_records = mysqli_num_rows($result1);

                $total_page = ceil($total_records / $limit);

                echo '<ul class="pagination admin-pagination">';
                if ($page > 1) {
                    echo '<li><a class="rounded btn" href="index.php?page=' . ($page - 1) . '">Prev</a></li>';
                }
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $page) {
                        $active = "active";
                    } else {
                        $active = "";
                    }
                    echo '<li class="' . $active . '"><a class="rounded btn" href="index.php?page=' . $i . '">' . $i . '</a></li>';
                }
                if ($total_page > $page) {
                    echo '<li><a class="rounded btn" href="index.php?page=' . ($page + 1) . '">Next</a></li>';
                }

                echo '</ul>';
            }
        }

        ?>
    </div>
</div>
</div>

<script>
    const data = <?= json_encode($data) ?>;

    const keys = [
        "title",
        "artist",
        "_type",
        "seller",
        "notes",
        "cost",
        "sell",
        "_date"
    ];

    const commaSeparatedString = [data.map((row) => keys.map((key) => row[key])).join("\n")]

    const csvBlob = new Blob([commaSeparatedString])

    const a2 = document.getElementById("a2")

    a2.href = URL.createObjectURL(csvBlob)
</script>

<?php include 'footer.php'; ?>
