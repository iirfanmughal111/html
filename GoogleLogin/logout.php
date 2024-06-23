<?php 
if ($_SESSION["token"] && $_SESSION["client"]){
    $_SESSION["client"]->revokeToken();
    header("Location:login.php");
}
else {
    echo "session token issue";
}
?>