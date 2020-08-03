<?php

function calOverallGrade($MarkedStudentOne)
{
    include "Connection.php";
    $sql = "SELECT Mark FROM mdb_ni8294f.Peer_Mark_Table where Marked_Student_ID = '$MarkedStudentOne' AND Submitted='1'";
    $results = $connection->query($sql);
    $rows = mysqli_num_rows($results);
    if ($rows == 2) {
        while ($rows = $results->fetch_assoc()) {
            $Marks[] = $rows['Mark'];
        }
        if (!empty($Marks[0]) && !empty($Marks[1])) {
            $a = $Marks[0] + $Marks[1];
            $b = $a / 2;
            $completeMark = $b;

            $sql = "UPDATE mdb_ni8294f.User_Table SET Overall_Grade = $completeMark  where User_ID = '$MarkedStudentOne'";
            $results = $connection->query($sql);
        }
    }
    return true;
}
