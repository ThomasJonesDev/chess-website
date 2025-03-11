<?php
    require "loginSession.php";
    require "dbConnection.php";
    //get input from text boxes
    $email = $_SESSION["email"];
    $currentPwd = $_POST["currentPwd"];
    $newPwd1 = $_POST["newPwd1"];
    $newPwd2 = $_POST["newPwd2"];

    //Check if new passwords match
    if (!$newPwd1 == $newPwd2) {
        echo "Passwords do not match";
        exit();
    }
    
    //Set up connection
    if(!$conn)
    {
        die("Connection Error");
    }
    $stmt = mysqli_stmt_init($conn);
    
    //check current credentials
    $sql = "SELECT * FROM chessdb.Users WHERE email=?;";
    if (!mysqli_stmt_prepare($stmt, $sql)){
        exit();
    }else{
        
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)){
            if (password_verify($currentPwd, $row["password"])) {
                //update database
                $passwordHash = password_hash($newPwd1, PASSWORD_DEFAULT);
                $sql = "UPDATE chessdb.Users SET password = ? WHERE email = ?;";

                if (!mysqli_stmt_prepare($stmt, $sql)){
                    die("EROOR on updating password");
                }
                mysqli_stmt_bind_param($stmt, "ss", $passwordHash, $email);
                mysqli_stmt_execute($stmt);
                header("Location: ../board.php");
            }
        }
    }
?>