<?php
    require "dbConnection.php";

    //GENERATE RAND PASSWORD
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, 8 );

    //TEMP WHILES EMAIL NOT WORKING ON LOCAL HOST
    $password = "password";
    $password = password_hash($password, PASSWORD_DEFAULT);
    //SEND EMAIL
    If(!empty($email = $_POST["email"]))
    {

        $sql = "SELECT * FROM chessdb.Users WHERE email = ?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s",  $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(empty($row = mysqli_fetch_assoc($result))){
            echo "<p> Account doesnt exist </p>";
            sleep(5);
            Header("Location: ../login.php");
            exit(); 
        }

        $subj = "New random password";
        mail($email, $subj, $password);
    
        //UPDATE PASSWORD
        $stmt = mysqli_stmt_init($conn);
        
        //check current credentials
        $sql = "UPDATE chessdb.Users
                SET password = ?
                WHERE email = ?;";
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $password, $email);
        mysqli_stmt_execute($stmt);
        Header("Location: ../login.php");
        
    }
?>