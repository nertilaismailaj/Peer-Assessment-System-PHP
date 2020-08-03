<?php

include "Connection.php";
$UserId =  $_GET['User_ID'];

$sql = "SELECT * FROM mdb_ni8294f.User_Table where User_ID = '$UserId'";
$result = $connection->query($sql);

$Marked_Student_Group = array();

while ($rows = $result->fetch_assoc()) {
  $Marked_Student_Group = $rows['Group_ID'];
  $_SESSION['OverallGradeLabel'] = $rows['Overall_Grade'];
}
$_SESSION['MarkedStudentID'] = $UserId;
$_SESSION['GroupIDLabel'] = $Marked_Student_Group[0];

$sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table where Marked_Student_ID = '$UserId' AND Submitted='1'";
if ($result = $connection->query($sql)) {
  $PeerMarkIDs = array();
  $GroupIDs = array();
  $MarkingStudentIDs = array();
  $Marks = array();
  $Comments = array();
  $Types = array();
  $Alts = array();

  while ($rows = $result->fetch_assoc()) {
    $PeerMarkIDs[] = $rows['Peer_Mark_ID'];
    $MarkingStudentIDs[] = $rows['Marking_Student_ID'];
    $Marks[] = $rows['Mark'];
    $Comments[] = $rows['Comment'];
    $Images[] = $rows['Images'];
    $Types[] = $rows['Type'];
    $Alts[] = $rows['Alt'];
  }

  $FirstPeerMarkID = $PeerMarkIDs[0];
  $FirstAlt = $Alts[0];
  if ($Types[0] != null) {
    $_SESSION['FirstImage'] = '<img src="GetImage.php?Peer_Mark_ID=' . $FirstPeerMarkID . '" alt="' .  $FirstAlt . '"name="FirstStudentImage" style="width:100px;height:100px;"/>  ';
  }

  $_SESSION['IDStudentOne'] = $MarkingStudentIDs[0];
  $_SESSION['markValueStudentOne'] = $Marks[0];
  $_SESSION['commentStudentOne'] = $Comments[0];
  $_SESSION['altStudentOne'] = $Alts[0];

  $SecondPeerMarkID = $PeerMarkIDs[1];
  $SecondAlt = $Alts[1];
  if ($Types[1] != null) {
    $_SESSION['SecondImage'] = '<img src="GetImage.php?Peer_Mark_ID=' . $SecondPeerMarkID . '" alt="' .  $SecondAlt . '"name="SecondStudentImage" style="width:100px;height:100px; />  ';
  }

  $_SESSION['IDStudentTwo'] = $MarkingStudentIDs[1];
  $_SESSION['markValueStudentTwo'] = $Marks[1];
  $_SESSION['commentStudentTwo'] = $Comments[1];
  $_SESSION['altStudentTwo'] = $Alts[1];
} else {
  echo "<br> Rows not selected $sql." . $mysqli->error;
}
