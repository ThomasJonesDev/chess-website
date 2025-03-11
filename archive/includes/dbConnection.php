<?php
    $servername = "localhost";
    $username = "root";
    $dbname = "chessdb";
    $password = "";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (! $conn) {
        $error = ("Database connection failed: " . mysqli_connect_error());
        $_SESSION["connection_error"] = $error;
    }
    elseif($conn){
        $_SESSION["connection_error"] = "false";
        //echo "<p> Connected Successfully </p> ";
    }
