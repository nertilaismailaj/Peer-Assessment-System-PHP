<?php

define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Home_student.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/MarkedStudentTwo.php');
$referer = $_SERVER['HTTP_REFERER'];
// if rererrer is not the form redirect the browser to the form
if ($referer != URLFORM && $referer != URLLIST) {
  header('Location: ' . URLFORM);
}


include "SessionProperties.php";
include "Connection.php";
include "overallGrade.php";

$MarkedStudentTwo = $_SESSION['labelStudentTwo'];
$MarkingStudentID = $_SESSION['login_user'];
$GroupID = $_SESSION['group_id'];
$LoadedImage =  $_SESSION['SecondImageType'];
$checkStudentTwo = array();

//Check button post for student 1 form*********************************************************************************************
if (isset($_POST['saveButtonStudentTwo'])) {
  //check mark field is populated
  if (preg_match('/[0123456789]/', $_POST['markValueStudentTwo'])) {
    $markValueStudentTwo = $_POST['markValueStudentTwo'];
    $markValueStudentTwo = htmlentities($markValueStudentTwo);
    $markValueStudentTwo = mysqli_real_escape_string($connection, $markValueStudentTwo);
  } else {
    $_SESSION['markValueStudentTwoLabel'] = '<span class="label label-danger">Please enter mark!</span>';
    array_push($checkStudentTwo, $_SESSION['markValueStudentTwoLabel']);
  }
  //check comment field is populated
  if (empty($_POST['commentStudentTwo'])) {
    $_SESSION['commentStudentTwoLabel'] = '<span class="label label-danger">Please enter only letters!</span>';
    array_push($checkStudentTwo, $_SESSION['commentStudentTwoLabel']);
  } else {
    $commentStudentTwo = $_POST['commentStudentTwo'];
    $commentStudentTwo = htmlentities($commentStudentTwo);
    $commentStudentTwo = mysqli_real_escape_string($connection, $commentStudentTwo);
  }

  //validate image uploaded
  if (!empty($LoadedImage) && $_FILES['StudentTwoFile']['size'] == 0) {
    echo "<script>console.log('Debug Objects: --' );</script>";
  } else if ($_FILES['StudentTwoFile']['size']  !== 0) {
    echo "<script>console.log('Debug Objects: 50' );</script>";
    if (!preg_match('/gif|png|x-png|jpeg/', $_FILES['StudentTwoFile']['type'])) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Only browser compatible images allowed!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else if ($_FILES['StudentTwoFile']['size'] > 16384) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Sorry file too large!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else if (!($handle = fopen($_FILES['StudentTwoFile']['tmp_name'], "r"))) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Error opening temp file!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else if (!($studentTwoImage = fread($handle, filesize($_FILES['StudentTwoFile']['tmp_name'])))) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Error reading temp file!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else {
      fclose($handle);
      $studentTwoImage = mysqli_real_escape_string($connection, $studentTwoImage);
      $studentTwoImageType = $_FILES["StudentTwoFile"]["type"];
    }
  } else { }

  if (!empty($checkStudentTwo)) {
    foreach ($checkStudentTwo as $value) {
      echo "<script>console.log('Debug Objects: " . $value . "--' );</script>";
      $value;
      header("location: Home_student.php");
    }
    unset($value);
  } else {

    //check if the relation between marking student and marked student is set
    $sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID='$MarkingStudentID' AND Marked_Student_ID='$MarkedStudentTwo'";
    $result = $connection->query($sql);
    $countRows = mysqli_num_rows($result);
    if ($countRows == 1) {  //is the relation is set just update row
      if (empty($studentTwoImageType)) {
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentTwo, Comment ='$commentStudentTwo', Submitted = 0  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentTwo'";
      } else {
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentTwo, Comment ='$commentStudentTwo',Image='$studentTwoImage', Type='$studentTwoImageType', Submitted = 0  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentTwo'";
      }
      if ($connection->query($sql)) {
        header("location: Home_student.php");
      } else {
        echo "<br> Record not inserted $sql." . $connection->error;
        $connection->close();
      }
    } else { //if the relation is not set then insert new row
      //insert into database
      if (empty($studentTwoImageType)) {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentTwo',$markValueStudentTwo ,'$commentStudentTwo',0)";
      } else {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Image,Type,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentTwo',$markValueStudentTwo ,'$commentStudentTwo','$studentTwoImage','$studentTwoImageType',0)";
      }
      if ($connection->query($sql)) {
        header("location: Home_student.php");
      } else {
        echo "<br> Record not inserted $sql." . $connection->error;
        $connection->close();
      }
    }
  }
}
//****************************************************************************************************************************/
else if (isset($_POST['deleteButtonStudentTwo'])) {
  //check if the relation between marking student and marked student is set
  $sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID='$MarkingStudentID' AND Marked_Student_ID='$MarkedStudentTwo'";
  $result = $connection->query($sql);
  $countRows = mysqli_num_rows($result);

  if ($countRows == 1) {  //is the relation is set just update row
    $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = null, Comment = null,Image= null, Type= null, Submitted = 0 WHERE  Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentTwo'";
    if ($connection->query($sql)) {
      header("location: Home_student.php");
    } else {
      echo "<br> Record not inserted $sql." . $connection->error;
      $connection->close();
    }
    //if the realtion is not set then set warning there is nothing to delete
  } else {
    $_SESSION['markValueStudentTwoLabel'] = '<span class="label label-danger">Please enter mark!</span>';
    $_SESSION['commentStudentTwoLabel'] = '<span class="label label-danger">Please enter a reason!</span>';
    header("location:Home_student.php");
  }
}
//****************************************************************************************************************************/
else if (isset($_POST['sumbitButtonStudentTwo'])) {
  //check mark field is populated
  if (preg_match('/[0123456789]/', $_POST['markValueStudentTwo'])) {
    $markValueStudentTwo = $_POST['markValueStudentTwo'];
    $markValueStudentTwo = htmlentities($markValueStudentTwo);
    $markValueStudentTwo = mysqli_real_escape_string($connection, $markValueStudentTwo);
  } else {
    $_SESSION['markValueStudentTwoLabel'] = '<span class="label label-danger">Please enter mark!</span>';
    array_push($checkStudentTwo, $_SESSION['markValueStudentTwoLabel']);
  }
  //check comment field is populated
  if (empty($_POST['commentStudentTwo'])) {
    $_SESSION['commentStudentTwoLabel'] = '<span class="label label-danger">Please enter only letters!</span>';
    array_push($checkStudentTwo, $_SESSION['commentStudentTwoLabel']);
  } else {
    $commentStudentTwo = $_POST['commentStudentTwo'];
    $commentStudentTwo = htmlentities($commentStudentTwo);
    $commentStudentTwo = mysqli_real_escape_string($connection, $commentStudentTwo);
  }

  //validate image uploaded
  if (!empty($LoadedImage) && $_FILES['StudentTwoFile']['size'] == 0) {
    echo "<script>console.log('Debug Objects: --' );</script>";
  } else if ($_FILES['StudentTwoFile']['size']  !== 0) {
    if (!preg_match('/gif|png|x-png|jpeg/', $_FILES['StudentTwoFile']['type'])) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Only browser compatible images allowed!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else if ($_FILES['StudentTwoFile']['size'] > 16384) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Sorry file too large!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else if (!($handle = fopen($_FILES['StudentTwoFile']['tmp_name'], "r"))) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Error opening temp file!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else if (!($studentTwoImage = fread($handle, filesize($_FILES['StudentTwoFile']['tmp_name'])))) {
      $_SESSION['imageStudentTwoLabel'] = '<span class="label label-danger">Error reading temp file!</span>';
      array_push($checkStudentTwo, $_SESSION['imageStudentTwoLabel']);
    } else {
      fclose($handle);
      $studentTwoImage = mysqli_real_escape_string($connection, $studentTwoImage);
      $studentTwoImageType = $_FILES["StudentTwoFile"]["type"];
    }
  } else { }

  if (!empty($checkStudentTwo)) {
    foreach ($checkStudentTwo as $value) {
      echo "<script>console.log('Debug Objects: " . $value . "--' );</script>";
      $value;
      header("location: Home_student.php");
    }
    unset($value);
  } else {
    echo "<script>console.log(183 );</script>";
    //check if the relation between marking student and marked student is set
    $sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID='$MarkingStudentID' AND Marked_Student_ID='$MarkedStudentTwo'";
    $result = $connection->query($sql);
    $countRows = mysqli_num_rows($result);
    if ($countRows == 1) {  //is the relation is set just update row
      echo "<script>console.log(189 );</script>";
      if (empty($studentTwoImageType)) {
        echo "<script>console.log(191 );</script>";
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentTwo, Comment ='$commentStudentTwo', Submitted = 1  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentTwo'";
      } else {
        echo "<script>console.log(194 );</script>";
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentTwo, Comment ='$commentStudentTwo',Image='$studentTwoImage', Type='$studentTwoImageType', Submitted = 1  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentTwo'";
      }
      echo $sql;
      if ($connection->query($sql)) {
        echo "<script>console.log(199 );</script>";
        calOverallGrade($MarkedStudentTwo);
        header("location: Home_student.php");
      } else {
        echo "<br> Record not inserted $sql." . $connection->error;
        $connection->close();
      }
      echo "<script>console.log(206 );</script>";
    } else { //if the relation is not set then insert new row
      //insert into database
      if (empty($studentTwoImageType)) {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentTwo',$markValueStudentTwo ,'$commentStudentTwo',1)";
      } else {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Image,Type,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentTwo',$markValueStudentTwo ,'$commentStudentTwo','$studentTwoImage','$studentTwoImageType',1)";
      }
      if ($connection->query($sql)) {
        calOverallGrade($MarkedStudentTwo);
        header("location: Home_student.php");
      } else {
        echo "<br> Record not inserted $sql." . $connection->error;
        $connection->close();
      }
    }
    echo "<script>console.log(222);</script>";
  }
}
