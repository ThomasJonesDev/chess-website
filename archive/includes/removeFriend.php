<?php
    require "loginSession.php";
    require "dbConnection.php";

    $friendID = $_GET["id"];
    $id = $_SESSION["userID"];
    $sql = "DELETE FROM chessdb.Friends WHERE (userID_1 = ? AND userID_2 = ?) OR (userID_2 = ? AND userID_1 = ?);";
                            
    echo var_dump($friendID);
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "iiii", $id, $friendID, $friendID, $id);
    mysqli_stmt_execute($stmt);
    //RESET URL
    Header("Location: ../social.php");

?>