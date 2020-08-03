<?php

define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Home_student.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/MarkedStudentOne.php');
$referer = $_SERVER['HTTP_REFERER'];
// if rererrer is not the form redirect the browser to the form
if ($referer != URLFORM && $referer != URLLIST) {
  header('Location: ' . URLFORM);
}


include "SessionProperties.php";
include "Connection.php";
include "overallGrade.php";

$MarkedStudentOne = $_SESSION['labelStudentOne'];
$MarkingStudentID = $_SESSION['login_user'];
$GroupID = $_SESSION['group_id'];
$LoadedImage =  $_SESSION['FirstImageType'];
$checkStudentOne = array();

//Check button post for student 1 form*********************************************************************************************
if (isset($_POST['saveButtonStudentOne'])) {
  //check mark field is populated
  if (preg_match('/[0123456789]/', $_POST['markValueStudentOne'])) {
    $markValueStudentOne = $_POST['markValueStudentOne'];
    $markValueStudentOne = htmlentities($markValueStudentOne);
    $markValueStudentOne = mysqli_real_escape_string($connection, $markValueStudentOne);
  } else {
    $_SESSION['markValueStudentOneLabel'] = '<span class="label label-danger">Please enter mark!</span>';
    array_push($checkStudentOne, $_SESSION['markValueStudentOneLabel']);
  }
  //check comment field is populated
  if (empty($_POST['commentStudentOne'])) {
    $_SESSION['commentStudentOneLabel'] = '<span class="label label-danger">Please enter only letters!</span>';
    array_push($checkStudentOne, $_SESSION['commentStudentOneLabel']);
  } else {
    $commentStudentOne = $_POST['commentStudentOne'];
    $commentStudentOne = htmlentities($commentStudentOne);
    $commentStudentOne = mysqli_real_escape_string($connection, $commentStudentOne);
  }

  //validate image uploaded
  if (!empty($LoadedImage) && $_FILES['StudentOneFile']['size'] == 0) {
    echo "<script>console.log('Debug Objects: --' );</script>";
  } else if ($_FILES['StudentOneFile']['size']  !== 0) {
    echo "<script>console.log('Debug Objects: 50' );</script>";
    if (!preg_match('/gif|png|x-png|jpeg/', $_FILES['StudentOneFile']['type'])) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Only browser compatible images allowed!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else if ($_FILES['StudentOneFile']['size'] > 16384) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Sorry file too large!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else if (!($handle = fopen($_FILES['StudentOneFile']['tmp_name'], "r"))) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Error opening temp file!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else if (!($studentOneImage = fread($handle, filesize($_FILES['StudentOneFile']['tmp_name'])))) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Error reading temp file!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else {
      fclose($handle);
      $studentOneImage = mysqli_real_escape_string($connection, $studentOneImage);
      $studentOneImageType = $_FILES["StudentOneFile"]["type"];
    }
  } else { }

  if (!empty($checkStudentOne)) {
    foreach ($checkStudentOne as $value) {
      echo "<script>console.log('Debug Objects: " . $value . "--' );</script>";
      $value;
      header("location: Home_student.php");
    }
    unset($value);
  } else {
    echo "78";
    //check if the relation between marking student and marked student is set
    $sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID='$MarkingStudentID' AND Marked_Student_ID='$MarkedStudentOne'";
    $result = $connection->query($sql);
    $countRows = mysqli_num_rows($result);
    if ($countRows == 1) {  //is the relation is set just update row
      if (empty($studentOneImageType)) {
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentOne, Comment ='$commentStudentOne', Submitted = 0  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentOne'";
      } else {
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentOne, Comment ='$commentStudentOne',Image='$studentOneImage', Type='$studentOneImageType', Submitted = 0  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentOne'";
      }
      if ($connection->query($sql)) {
        header("location: Home_student.php");
      } else {
        echo "<br> Record not inserted $sql." . $connection->error;
        $connection->close();
      }
    } else { //if the relation is not set then insert new row
      //insert into database
      if (empty($studentOneImageType)) {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentOne',$markValueStudentOne ,'$commentStudentOne',0)";
      } else {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Image,Type,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentOne',$markValueStudentOne ,'$commentStudentOne','$studentOneImage','$studentOneImageType',0)";
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
else if (isset($_POST['deleteButtonStudentOne'])) {
  //check if the relation between marking student and marked student is set
  $sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID='$MarkingStudentID' AND Marked_Student_ID='$MarkedStudentOne'";
  $result = $connection->query($sql);
  $countRows = mysqli_num_rows($result);

  if ($countRows == 1) {  //is the relation is set just update row
    $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = null, Comment = null,Image= null, Type= null, Submitted = 0 WHERE  Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentOne'";
    if ($connection->query($sql)) {
      header("location: Home_student.php");
    } else {
      echo "<br> Record not inserted $sql." . $connection->error;
      $connection->close();
    }
    //if the realtion is not set then set warning there is nothing to delete
  } else {
    $_SESSION['markValueStudentOneLabel'] = '<span class="label label-danger">Please enter mark!</span>';
    $_SESSION['commentStudentOneLabel'] = '<span class="label label-danger">Please enter a reason!</span>';
    header("location:Home_student.php");
  }
}
//****************************************************************************************************************************/
else if (isset($_POST['sumbitButtonStudentOne'])) {
  //check mark field is populated
  if (preg_match('/[0123456789]/', $_POST['markValueStudentOne'])) {
    $markValueStudentOne = $_POST['markValueStudentOne'];
    $markValueStudentOne = htmlentities($markValueStudentOne);
    $markValueStudentOne = mysqli_real_escape_string($connection, $markValueStudentOne);
  } else {
    $_SESSION['markValueStudentOneLabel'] = '<span class="label label-danger">Please enter mark!</span>';
    array_push($checkStudentOne, $_SESSION['markValueStudentOneLabel']);
  }
  //check comment field is populated
  if (empty($_POST['commentStudentOne'])) {
    $_SESSION['commentStudentOneLabel'] = '<span class="label label-danger">Please enter only letters!</span>';
    array_push($checkStudentOne, $_SESSION['commentStudentOneLabel']);
  } else {
    $commentStudentOne = $_POST['commentStudentOne'];
    $commentStudentOne = htmlentities($commentStudentOne);
    $commentStudentOne = mysqli_real_escape_string($connection, $commentStudentOne);
  }

  //validate image uploaded
  if (!empty($LoadedImage) && $_FILES['StudentOneFile']['size'] == 0) {
    echo "<script>console.log('Debug Objects: --' );</script>";
  } else if ($_FILES['StudentOneFile']['size']  !== 0) {
    if (!preg_match('/gif|png|x-png|jpeg/', $_FILES['StudentOneFile']['type'])) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Only browser compatible images allowed!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else if ($_FILES['StudentOneFile']['size'] > 16384) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Sorry file too large!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else if (!($handle = fopen($_FILES['StudentOneFile']['tmp_name'], "r"))) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Error opening temp file!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else if (!($studentOneImage = fread($handle, filesize($_FILES['StudentOneFile']['tmp_name'])))) {
      $_SESSION['imageStudentOneLabel'] = '<span class="label label-danger">Error reading temp file!</span>';
      array_push($checkStudentOne, $_SESSION['imageStudentOneLabel']);
    } else {
      fclose($handle);
      $studentOneImage = mysqli_real_escape_string($connection, $studentOneImage);
      $studentOneImageType = $_FILES["StudentOneFile"]["type"];
    }
  } else { }

  if (!empty($checkStudentOne)) {
    foreach ($checkStudentOne as $value) {
      echo "<script>console.log('Debug Objects: " . $value . "--' );</script>";
      $value;
      header("location: Home_student.php");
    }
    unset($value);
  } else {
    //check if the relation between marking student and marked student is set
    $sql = "SELECT * FROM mdb_ni8294f.Peer_Mark_Table WHERE Marking_Student_ID='$MarkingStudentID' AND Marked_Student_ID='$MarkedStudentOne'";
    $result = $connection->query($sql);
    $countRows = mysqli_num_rows($result);
    if ($countRows == 1) {  //is the relation is set just update row
      if (empty($studentOneImageType)) {
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentOne, Comment ='$commentStudentOne', Submitted = 1  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentOne'";
      } else {
        $sql = "UPDATE mdb_ni8294f.Peer_Mark_Table SET Mark = $markValueStudentOne, Comment ='$commentStudentOne',Image='$studentOneImage', Type='$studentOneImageType', Submitted = 1  WHERE Marking_Student_ID = '$MarkingStudentID' AND Marked_Student_ID = '$MarkedStudentOne'";
      }
      if ($connection->query($sql)) {
        calOverallGrade($MarkedStudentOne);
        header("location: Home_student.php");
      } else {
        echo "<br> Record not inserted $sql." . $connection->error;
        $connection->close();
      }
    } else { //if the relation is not set then insert new row
      //insert into database
      if (empty($studentOneImageType)) {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentOne',$markValueStudentOne ,'$commentStudentOne',1)";
      } else {
        $sql = "INSERT INTO mdb_ni8294f.Peer_Mark_Table(Group_ID, Marking_Student_ID, Marked_Student_ID,Mark,Comment,Image,Type,Submitted) VALUES ($GroupID,'$MarkingStudentID','$MarkedStudentOne',$markValueStudentOne ,'$commentStudentOne','$studentOneImage','$studentOneImageType',1)";
      }
      if ($connection->query($sql)) {
        calOverallGrade($MarkedStudentOne);
        header("location: Home_student.php");
      } else {
        echo "<br> Record not inserted $sql." . $connection->error;
        $connection->close();
      }
    }
  }
}
