<?php
    $con = mysqli_connect("localhost","root","","app");
    if(mysqli_connect_errno()){
        echo "Failed to connect to MySql Database".mysqli_connect_error();
    }
?>
