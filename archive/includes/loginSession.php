<?php
    session_start();
        //echo "<p>" . $_SESSION["logged_in"] . "</p>";
        if($_SESSION["logged_in"] != "true"){
            //Hearder caused infinite loop
            //header("Location: board.php");
            exit();
        }
?>