<?php
include "SessionProperties.php";
include "Connection.php";

//get values from SessionProperties
$MarkingStudentID = $_SESSION['login_user'];
$GroupID = $_SESSION['group_id'];
//get other group members
$sql = "SELECT User_ID FROM mdb_ni8294f.User_Table WHERE  User_ID <> '$MarkingStudentID' AND  Group_ID = '$GroupID'";
$result = $connection->query($sql);
$studentIDs = array();
while ($rows = $result->fetch_assoc()) {
  $studentIDs[] = $rows['User_ID'];
}
$MarkedStudentOne = $studentIDs[0];
$MarkedStudentTwo = $studentIDs[1];
$_SESSION['labelStudentOne'] = $MarkedStudentOne;
$_SESSION['labelStudentTwo'] = $MarkedStudentTwo;

//First Student 
$sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentOne'";
if ($result = $connection->query($sql)) {
  $checkingMarked1 = array();
  $PeerMarkID1 = array();
  $studentMark1 = array();
  $studentComment1 = array();
  $typesArray1 = array();
  $submittedRow1 = array();
  while ($rows = $result->fetch_assoc()) {
    $checkingMarked1[] = $rows['Marked_Student_ID'];
    $PeerMarkID1[] = $rows['Peer_Mark_ID'];
    $studentMark1[] = $rows['Mark'];
    $studentComment1[] = $rows['Comment'];
    $typesArray1[] = $rows['Type'];
    $submittedRow1[] = $rows['Submitted'];
  }
}

if (!empty($checkingMarked1[0])) {
  $FirstPeerMarkID = $PeerMarkID1[0]; //get peerid for first marked student 
  if ($typesArray1[0] != null) { //if types coulmn not empty then set the below values 
    $_SESSION['FirstImageType'] = $typesArray1[0];
    $_SESSION['FirstImage'] = '<img src="GetImage.php?Peer_Mark_ID=' . $FirstPeerMarkID . '" name="FirstStudentImage" style="width:100px;height:100px;/>  ';
  }
  //check if the review was submitted 
  if ($submittedRow1[0] == 1) {
    $_SESSION['FirstSubmitted']  = $submittedRow1[0];
  }
  $_SESSION['markValueStudentOneValidated'] = $studentMark1[0]; //get the value of the mark
  $_SESSION['commentStudentOneValidated'] = $studentComment1[0]; //get the value of the comment
}



//Second Student
$ssql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentTwo'";
if ($result = $connection->query($ssql)) {
  $checkingMarked2 = array();
  $PeerMarkID2 = array();
  $studentMark2 = array();
  $studentComment2 = array();
  $typesArray2 = array();
  $submittedRow2 = array();
  while ($rows = $result->fetch_assoc()) {
    $checkingMarked2[] = $rows['Marked_Student_ID'];
    $PeerMarkID2[] = $rows['Peer_Mark_ID'];
    $studentMark2[] = $rows['Mark'];
    $studentComment2[] = $rows['Comment'];
    $typesArray2[] = $rows['Type'];
    $submittedRow2[] = $rows['Submitted'];
  }
}

if (!empty($checkingMarked2[0])) {
  $SecondPeerMarkID = $PeerMarkID2[0]; //get peerid for the second marked student
  if ($typesArray2[0] !== null) { //if types coulmn not empty then set the below values 
    $_SESSION['SecondImageType'] = $typesArray2[0];
    $_SESSION['SecondImage'] = '<img src="GetImage.php?Peer_Mark_ID=' . $SecondPeerMarkID . '" name="SecondStudentImage" style="width:100px;height:100px;/>  ';
  }
  //check if the review was submitted 
  if ($submittedRow2[0] == 1) {
    $_SESSION['SecondSubmitted']  = $submittedRow2[0];
  }
  $_SESSION['markValueStudentTwoValidated'] = $studentMark2[0];
  $_SESSION['commentStudentTwoValidated'] =  $studentComment2[0];
}
