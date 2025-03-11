<?php
require "includes/loginSession.php";
require "includes/dbConnection.php";
$id = $_SESSION["userID"];
$username = $_SESSION["username"];
//header("Refresh:5");
?>

<!DOCTYPE html>
<html lang="en" style="height:100%">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Chess Game - Friends</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body style="height:100%">
    <!-- NAVIGATION BAR ######################################### -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container justify-content-start">
            <button type="button" class="btn btn-secondary" data-bs-toggle="offcanvas" data-bs-target="#menu">Menu</button>
            <ul class="navbar-nav pl-3">
                <li class="nav-item">
                    <a class="nav-link" href="board.php">Play</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="social.php">Friends</a>
                </li>
            </ul>
        </div>
        <div class="container-fluid justify-content-center">
            <span class="navbar-brand">Chess Game - Player: <?= $username ?></span>
        </div>
        <div class="container justify-content-end">
            <a href="login.php" class="btn btn-danger" role="button"><span style="color:#fff;">Logout</span></a>
        </div>
    </nav>

    <!-- body -->
    <div class="container mt-5">
        <div class="row" style="">
            <div class="col-md-4" style="">
                <div class="card p-2" style="height:100%">
                    <h3 class="text-center">Send Friend Request</h3>
                    <form method="post" action="social.php">
                        <div class="input-group mb-3">
                            <input type="text" name="reqout" class="form-control" placeholder="New friend">
                            <button type="submit" class="btn btn-primary">SEND</button>
                        </div>
                    </form>
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
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        $friendId = $row["userID"];

                        //TODO: CHECK THAT ARNTS ALREADY FRIENDS
                        //TODO: CHECK THAT REQUEST DOESNT ALREADY EXIT (BOTH WAYS)

                        //SEND FRIEND REQUESTS
                        $sql = "INSERT INTO chessdb.FriendRequests (from_user, to_user) VALUE (?, ?);";
                        mysqli_stmt_prepare($stmt, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $id, $friendId);
                        mysqli_stmt_execute($stmt);
                    }
                    ?>
                    <hr>
                    <h3 class="text-center">Recieved Friend Requests</h3>
                    <table>
                        <?php
                        //GET INCOMING REQUESTS FROM DB
                        $stmt = mysqli_stmt_init($conn);
                        $sql = "SELECT username, userID 
                        FROM chessdb.Users, chessdb.FriendRequests 
                        WHERE chessdb.FriendRequests.to_user = ? AND chessdb.Users.userID = chessdb.FriendRequests.from_user;";
                        mysqli_stmt_prepare($stmt, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $id);
                        mysqli_stmt_execute($stmt);
                        $results = mysqli_stmt_get_result($stmt);


                        while ($row = mysqli_fetch_assoc($results)) {
                            $friendReqId = $row["userID"];
                            $yes = True;
                            $no = False;
                            echo "<p>"
                                . $row['username'] . " 
                            <a class='float-end mx-2 text-decoration-none font-weight-bold' href='social.php?accept=$no&friendReqId=$friendReqId'>DENY</a>
                            <a class='float-end mx-2 text-decoration-none font-weight-bold' href='social.php?accept=$yes&friendReqId=$friendReqId'>ACCEPT</a>
                            </p>";
                        }


                        //RESPONSE

                        if (isset($_GET['accept']) && isset($_GET['friendReqId'])) {
                            $RespAns = $_GET["accept"];
                            $RespId = $_GET["friendReqId"];
                            //ACCEPT
                            if ($RespAns == True) {
                                //ADD TO DB
                                $sql = "INSERT INTO chessdb.Friends (userID_1, userID_2) VALUES (?,?);";
                                mysqli_stmt_prepare($stmt, $sql);
                                mysqli_stmt_bind_param($stmt, "ii", $id, $RespId);
                                mysqli_stmt_execute($stmt);
                            }
                            //REMOVE FROM FRIEND REQUESTS
                            $sql = "DELETE FROM FriendRequests WHERE (to_user = ? AND from_user = ?) OR (to_user = ? AND from_user = ?);";
                            mysqli_stmt_prepare($stmt, $sql);
                            mysqli_stmt_bind_param($stmt, "iiii", $id, $RespId, $RespId, $id);
                            mysqli_stmt_execute($stmt);
                            //RESET URL
                            Header("Location: social.php");
                        }

                        ?>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-2" style="height:100%">
                    <h3 class="text-center">Friends List</h3>
                    <table>
                        <!-- GET FRIENDS LIST FROM DB -->
                        <?php

                        $stmt = mysqli_stmt_init($conn);
                        $sql = "SELECT userID, username
                        FROM chessdb.Friends, chessdb.Users
                        WHERE (chessdb.Friends.userID_1 = ? OR chessdb.Friends.userID_2 = ?) 
                        AND (chessdb.Users.userID = chessdb.Friends.userID_1 OR chessdb.Users.userID = chessdb.Friends.userID_2);";
                        mysqli_stmt_prepare($stmt, $sql);
                        mysqli_stmt_bind_param($stmt, "ii", $id, $id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        //LOOP THROUGH REQUESTS AND DISPLAY
                        //echo "<div class='list-group'>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($row["userID"] != $id) {
                                $friendId = $row["userID"];
                                $friendUsername = $row["username"];
                                $_SESSION["friendID"] = $friendId;
                                echo "<p>
                                <a>$friendUsername</a>
                                <a class='float-end mx-1 text-decoration-none font-weight-bold text-danger' href='includes/removeFriend.php?id=$friendId'>Remove</a>
                                <a class='float-end me-5 text-decoration-none text-primary' href='social.php?friend=$friendUsername&id=$friendId'>Message</a>
                                </p>";
                            }
                        }

                        


                        ?>
                    </table>
                </div>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card p-2" style="height:100%">
                    <h3 class="text-center">Chat with friends</h3>
                    <?php
                    // causes error but breaks everything if removed
                    if (isset($_GET["friend"], $_GET["id"]))
                    {
                        $friend = $_GET["friend"];
                        $friendID = $_GET["id"];
                    }
                    else
                    {
                        $friend = "";
                        $friendID = -1;
                    }
                    ?>
                    <form method="post" action="includes/sendMessage.php">
                        <input type="hidden" name="id" value="<?= $friendID ?>">
                        <input type="hidden" name="username" value="<?= $friend ?>">
                        <div class="input-group mb-3">
                            <input readonly type="text" name="chatfriend" class="form-control" placeholder="Friend" value=<?= $friend ?> ></input>
                            <button type="button" class="btn btn-primary" onClick="location.href='board.php'">To Game</button>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="textbox" placeholder="Enter message" name="textbox" rows="5"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-2">SEND</button>
                        </div>
                    </form>
                    <h4 class="pt-2">Messages:</h4>
                    <div class= "mt-2">
                            <ul class="list-group pb-2">
                    <?php
                    // Working now 
                    //SEND MESSAGE (moved to own file -> sendMessage.php)
                    //DISPLAY MESSSAGES (moved to own file -> getMessages.php)
                    include "includes/getMessages.php";
                    ?>  
                            </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>


    </div>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start" id="menu">
        <div class="offcanvas-header">
            <!-- sidebar header -->
            <h1 class="offcanvas-title">Menu</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!-- sidebar body -->
            <a type="button" class="btn" href="changePassword.php">Change Password</a>
        </div>
    </div>

</body>

</html>