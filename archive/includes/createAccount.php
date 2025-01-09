<?php
    require "dbConnection.php";

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["passwordConfirm"];

    if ($password != $confirmPassword){
        echo "Password failed to match";
        exit();
    }

    $sql = "SELECT * FROM chessdb.Users WHERE username = ? OR email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        echo "<p> statement error </p>";
        exit();}
    else{
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)){
            echo $row["username"];
            echo $row["email"];
            if($username == $row["username"]){
                echo "<p> duplicate username </p>";
                exit();
            }
            if($email == $row["email"]){
                echo "<p> duplicate email </p>";
                exit();
            }
        }else{
            //echo "<p> checks passed </p>";
            $sql = "INSERT INTO chessdb.Users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)){
                echo "<p> statement error </p>";
                exit();
            }else{
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                //returns back to login page
                header("Location: ../login.php");
                exit();
            }
        }
    }
?>
