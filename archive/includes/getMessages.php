<?php
require "dbConnection.php";

$friendId = $_SESSION["friendID"];
$id = $_SESSION["userID"];

//echo "<p>" . $friendId . "</p>";
//echo "<p>" . $id . "</p>";

$stmt = mysqli_stmt_init($conn);
$sql = "SELECT M.`UserID-From`, M.`UserID-To`, M.messageContent, M.dateSent, U.userID, U.username
        FROM chessdb.Messages AS M INNER JOIN chessdb.Users AS U
        ON ((M.`UserID-From` = ? and M.`UserID-To` = ?) or (M.`UserID-From` = ? and M.`UserID-To` = ?))
        AND (M.`UserID-From` = U.userID)
        ORDER BY dateSent;";
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "iiii", $id, $friendID, $friendID, $id);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($results))
    {
                       
        //display chat
        echo "<li class='list-group-item'>";
        echo "<p><b>" . $row["username"] . "</b> (" . $row["dateSent"] .  "): " ."</p>";
        echo "<p>" . $row["messageContent"] . "</p>";
        echo "</li>";
    }