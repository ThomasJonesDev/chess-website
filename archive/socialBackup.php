<?php
    require "includes/loginSession.php";
    require "includes/dbConnection.php";
    $id = $_SESSION["userID"];
    $username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Chess Game - Friends</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    a:link	{color:#f00; text-decoration:none;}
    a:visited{color:#f00; text-decoration:none;}
    a:active{color:#fff; background:#c00; text-decoration:none;}
    a:hover	{color:#efff; background:#c00; text-decoration:none;}
    </style>
</head>
<body style = "background: white;">
    <!-- ########################### NAVIGATION BAR ########################### -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container justify-content-start">
            <button type="button" class="btn btn-secondary" data-bs-toggle="offcanvas" data-bs-target="#menu">Menu</button>
            <ul class="navbar-nav pl-3">ye
                <li class="nav-item">
                    <a class="nav-link" href="board.php">Play</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="social.php">Friends</a>
                </li>
            </ul>
        </div>
        <div class="container-fluid justify-content-center">
            <span class="navbar-brand">Chess Game - Player : <?=$username?></span>
        </div>
        <div class="container justify-content-end">
            <a href="login.php" class="btn btn-danger" role="button">Logout</a>
        </div>
    </nav>

    <!-- body -->
    <div class="row m-3">

    <!-- ########################### IN / OUT FRIEND REQUESTS ########################### -->

    <div class="container-flex m-5 col border shadow-lg" style="background:#212529; color:white; border-radius:25px;">
        
        <br><h3 style="text-align:center"><u>Friend Request Out</u></h3><br>

        <table align=center><td>
            <form method="post" action="social.php">
                <input type="hidden" name="id" value="<?=$id?>">
                <input style="border-radius:8px; text-align:center;" type="text" name="reqout"> New friend &nbsp; &nbsp;
                <input style="border-radius:8px;" type="submit" value="SEND"><br>
            </form>
        </table>
        <!--SEND FRIEND REQUEST-->
        <?php

            if (!empty($_POST["reqout"])) {
                $reqout = $_POST["reqout"];

                //CONNECT TO DB
                $stmt = mysqli_stmt_init($conn);
                //GET USER IDS
                $sql = "SELECT userID FROM chessdb.Users WHERE username = ?;";
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "s", $reqout);
                mysqli_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                $friendId = $row["userID"];

                //TODO: CHECK THAT ARNTS ALREADY FRIENDS
                //TODO: CHECK THAT REQUEST DOESNT ALREADY EXIT (BOTH WAYS)

                //SEND FRIEND REQUESTS
                $sql = "INSERT INTO chessdb.FriendRequests (from_user, to_user) VALUE (?, ?);";
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "ii", $id, $friendId);
                mysqli_execute($stmt);
                    
            }
        ?>    
        <hr>
        <h3 style="text-align:center"><u> Friend Requests In </u></h3>
        
        <!-- ACCEPT/DENY REQUESTS -->
        <table width=100% height=750><td valign=top style='padding:30px; font-size:22px'>
        <table>
        <?php 
            //GET INCOMING REQUESTS FROM DB
            $stmt = mysqli_stmt_init($conn);
            $sql = "SELECT username, userID 
                    FROM chessdb.Users, chessdb.FriendRequests 
                    WHERE chessdb.FriendRequests.to_user = ? AND chessdb.Users.userID = chessdb.FriendRequests.from_user;";
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_execute($stmt);
            $results = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($results)) {
                $friendReqId = $row["userID"];
                $yes = True;
                $no = False;
                echo "<p>" . $row["username"] . " <a href='social.php?accept=$yes&friendReqId=$friendReqId'>YES</a> <a href='social.php?accept=$no&friendReqId=$friendReqId'>NO</a> <p>";
            }

            //RESPONSE
            
            if(isset($_GET['accept']) && isset($_GET['friendReqId']))
            {
                $RespAns = $_GET["accept"];
                $RespId = $_GET["friendReqId"];
                //ACCEPT
                if ($RespAns == True)
                {
                    //ADD TO DB
                    $sql = "INSERT INTO chessdb.Friends (userID_1, userID_2) VALUES (?,?);";
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_bind_param($stmt, "ii", $id, $RespId);
                    mysqli_execute($stmt);
                }
                //REMOVE FROM FRIEND REQUESTS
                $sql = "DELETE FROM FriendRequests WHERE (to_user = ? AND from_user = ?) OR (to_user = ? AND from_user = ?);";
                    mysqli_stmt_prepare($stmt, $sql);   
                    mysqli_stmt_bind_param($stmt, "iiii", $id, $RespId, $RespId, $id);
                    mysqli_execute($stmt);
                    //RESET URL
                Header("Location: social.php");
            }
            
        ?>
        </table>
        </table>

    </div>

<!--########################################### FRIENDSLIST-->

        <div class="container-flex m-5 col border shadow-lg" style="background:#212529; color:white; border-radius:25px;">
            <br><h3 style="text-align:center"><u> Friends List </u></h3>
            <table width=100% height=750><td valign=top style="padding:30px; font-size:22px">
            <!-- GET FRIENDS LIST FROM DB -->
            <?php
                $stmt = mysqli_stmt_init($conn);
                $sql = "SELECT userID, username
                FROM chessdb.Friends, chessdb.Users
                WHERE (chessdb.Friends.userID_1 = ? OR chessdb.Friends.userID_2 = ?) 
                AND (chessdb.Users.userID = chessdb.Friends.userID_1 OR chessdb.Users.userID = chessdb.Friends.userID_2);";
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "ii", $id, $id);
                mysqli_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
    
                //LOOP THROUGH REQUESTS AND DISPLAY
                while ($row = mysqli_fetch_assoc($result))
                {
                    if ($row["userID"] != $id) {
                        $friendId = $row["userID"];
                        $friendUsername = $row["username"];
                        echo "<p><a href='social.php?friend=$friendUsername&id=$friendId'>".$friendUsername."</a></p>";
                    }
                }
            ?>
            </table>
        </div>

<!-- ########################################### CHAT WITH FRIENDS -->

        <div class="container-flex m-5 col border shadow-lg" style="background:#212529; color:white; border-radius:25px;">
            <br><h3 style="text-align:center"><u> Chat with friends </u></h3><br>

            <?php
                $friend=$_GET["friend"];
                $friendID = $_GET["id"]
            ?>

            <form method="post" action="social.php">
                <input readonly style="border-radius:8px; text-align:center;" type="text" name="chatfriend" value=<?=$friend?>> Friend &nbsp; &nbsp; &nbsp; 
                <button style="border-radius:5px;" onClick="">To Game</button>
                <br><br>
                <textarea style="border-radius:8px;" name="textbox" rows="5" cols="50"></textarea><br><br>
                <input type="submit" value="SEND" style="border-radius:8px;"><br><hr>
            </form>

            <?php //SEND MESSAGE

                if (!empty($msgStr = $_POST["textbox"]))
                {
                    $mysqltime = date ('Y-m-d H:i:s');

                    $stmt = mysqli_stmt_init($conn);
                    $sql = "INSERT INTO chessdb.Messages (`UserID-From`, `userId-To`, messageContent, dateSent) VALUES (?,?,?,?);";
                    mysqli_stmt_prepare($stmt, $sql);
                    $temp = "date";
                    mysqli_stmt_bind_param($stmt, "iiss", $id, $friendId, $msgStr, $mysqltime);
                    mysqli_execute($stmt);

                }
                
                //DISPLAY MESSSAGES
                $chatfriend = $_GET["friend"];
                
                    $stmt = mysqli_stmt_init($conn);
                    $sql = "SELECT * 
                            FROM chessdb.Messages 
                            WHERE (`UserID-From` = ? and `UserID-To` = ?) or (`UserID-From` = ? and `UserID-To` = ?)
                            ORDER BY dateSent;";
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_bind_param($stmt, "iiii", $id, $friendID, $friendID, $id);
                    mysqli_execute($stmt);
                    $results = mysqli_stmt_get_result($stmt);
                    while ($row = mysqli_fetch_assoc($results))
                    {
                       
                        //display chat
                        echo "<p>" . $row["dateSent"] . " : " . $row["messageContent"] . "</p>";
                    }
                    
            ?>

        </div>
    </div> 

<!-- ############################################################# -->

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start" id="menu">
        <div class="offcanvas-header">
            <!-- sidebar header -->

            <h1 class="offcanvas-title">Menu</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
        
            <!-- sidebar body -->

        </div>
</div>

</body>
</html>
