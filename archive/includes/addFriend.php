<?php
    session_start();
    require "dbConnection.php";
    $userID = $_SESSION["userID"];
    $username = $_POST["username"];
    echo "<p>" . $userID . "</p>";
    echo "<p>" . $username . "</p>";

    // 1. lookup user (done)
    // 2. check if already friend
    // 3. Added new pending friend entry to db

    $sql = "SELECT userID FROM chessdb.users WHERE username = ?;";
    $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "<p> statement error </p>";
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result)){
                $userID_Friend = $row["userID"];
                echo "<p>" . $userID_Friend . "</p>";
                $sql = "SELECT * FROM chessdb.friends WHERE (userID_1 = ? AND userID_2 = ?) OR (userID_1 = ? AND userID_2 = ?);";

            }else{
                echo "<p> Error - No User Found </p>";
                exit();
            }
        }
?>