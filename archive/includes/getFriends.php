<?php
    require "dbConnection.php";
    $sql = "SELECT * FROM chessdb.friends WHERE userID_1 = ? OR userID_2 = ?";
    // idea set userID as session after login
    $userID = $_SESSION["userID"];
    //echo "<p>" . $userID . "</p>";
    $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "<p> statement error </p>";
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "ss", $userID, $userID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)){
                $userID_1 = $row["userID_1"];
                $userID_2 = $row["userID_2"];
                //echo "<p>" . $row["relationID"] . "</p>";
                //echo "<p>" . $row["userID_1"] . "</p>";
                //echo "<p>" . $row["userID_2"] . "</p>";
                if($row["userID_1"] != $userID){
                    $sql = "SELECT username FROM chessdb.users WHERE userID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                        echo "<p> statement error </p>";
                        exit();
                    }else{
                        mysqli_stmt_bind_param($stmt, "s", $userID_1);
                        mysqli_stmt_execute($stmt);
                        $resultUser1 = mysqli_stmt_get_result($stmt);
                        if($rowUser1 = mysqli_fetch_assoc($resultUser1)){
                            echo '<li class="list-group-item">' . $rowUser1["username"] . '</li>';
                            //echo "<p> if 1 </p>";
                        }
                    }
                } else if($row["userID_2"] != $userID){
                    $sql = "SELECT username FROM chessdb.users WHERE userID = ?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)){
                        echo "<p> statement error </p>";
                        exit();
                    }else{
                        mysqli_stmt_bind_param($stmt, "s", $userID_2);
                        mysqli_stmt_execute($stmt);
                        $resultUser2 = mysqli_stmt_get_result($stmt);
                        if($rowUser2 = mysqli_fetch_assoc($resultUser2)){
                            echo '<li class="list-group-item">' . $rowUser2["username"] . '</li>';
                            //echo "<p> if 2 </p>";
                        }
                    }
                }
            }
        }
?>