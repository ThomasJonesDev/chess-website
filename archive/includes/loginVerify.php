<?php
    session_start();
    require "dbConnection.php";
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM chessdb.Users WHERE email=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)){
            if (password_verify($password, $row["password"])) {
                $_SESSION["logged_in"] = "true";
                $_SESSION["failed"] = "false";
                $_SESSION["userID"] = $row["userID"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["email"] = $email;
                header("Location: ../board.php");
                exit;
            }
            else{
                $_SESSION["failed"] = "true";
                header("Location: ../login.php");
            }
        }
        else {
            $_SESSION["failed"] = "true";
            header("Location: ../login.php");
        }
        exit;
    }
?>