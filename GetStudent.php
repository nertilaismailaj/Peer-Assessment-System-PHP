<?php
//URL mandate
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
  $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $redirect);
  exit();
}

include "Connection.php";
include "SessionProperties.php";
include "LoadStudent.php";

?>
<!--Start of html for tutor home page -->
<!DOCTYPE html>
<html lang="en-GB">

<head>
  <title>PAS - Peer Assessment System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--Adding Bootstrap to style the all page -->
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/css.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <style>
    .panelBackground {
      background: rgba(255, 255, 255, .9);
    }

    .tr {
      border-bottom: 1px solid #A9A9A9;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <!--    Nav bar-->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" style="color:Black;">Peer Marking System | University of Greenwich </a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li><a href="https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Home_tutor.php"> <span class="glyphicon glyphicon-home"></span> Home </a></li>
            <li><a href="https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/SearchStudent.php"> <span class="glyphicon glyphicon-search"></span> Search Review</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li> <a> Tutor Mode | <span class="glyphicon glyphicon-user"></span> <?php if (isset($_SESSION['login_user'])) {
                                                                                    echo $_SESSION['login_user'];
                                                                                  } ?> </a></li>
            <li>
              <form class="navbar-form" method="post" action="Logout.php">
                <button name="signOutButton" type="submit" class="btn btn-default">
                  <span class="glyphicon glyphicon-log-out"></span>
                  Sign out
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Body -->
    <div class="container">
      <p></p>
      <div class="panel  panel-default panelBackground">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-5"></div>
            <div class="col-sm-3">
              <img class="img-responsive" src="images/userOne.png" alt="user_icon" width="150" height="150">
            </div>
            <div class="col-sm-3"></div>
          </div>
          <div class="form-group row content">
            <div class="col-sm-2"></div>
            <div class="col-sm-3">
              <label for="GroupIDLabel">Group ID:</label>
              <label name="GroupIDLabel">
                <?php if (isset($_SESSION['GroupIDLabel'])) {
                  echo $_SESSION['GroupIDLabel'];
                } else {
                  echo "nan!";
                }
                ?>
              </label>
            </div>
            <div class="col-sm-3">
              <label for="MarkedStudentID">Student ID:</label>
              <label name="MarkedStudentID">
                <?php if (isset($_SESSION['MarkedStudentID'])) {
                  echo $_SESSION['MarkedStudentID'];
                } else {
                  echo "nan!";
                }
                ?>
              </label>
            </div>
            <div class="col-sm-3">
              <label for="OverallGradeLabel">Overall Grade:</label>
              <label name="OverallGradeLabel">
                <?php if (isset($_SESSION['OverallGradeLabel'])) {
                  echo $_SESSION['OverallGradeLabel'];
                } else {
                  echo "Not calculated yet!";
                }
                ?>
              </label>
            </div>
            <div class="col-sm-1"></div>
          </div>
          <hr>
          <div class="form-group row content">
            <div class="col-sm-5"></div>
            <div class="col-sm-3"><label> First review </label></div>
            <div class="col-sm-3"></div>
          </div>
          <div class="form-group row content">
            <div class="col-sm-9">
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><label>Student ID:</label></div>
                <div class="col-sm-2">
                  <label name="IDStudentOne">
                    <?php if (isset($_SESSION['IDStudentOne'])) {
                      echo $_SESSION['IDStudentOne'];
                    } else {
                      echo "nan!";
                    }
                    ?>
                  </label>
                </div>
                <div class="col-sm-5"></div>
              </div>
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><label>Mark Given:</label></div>
                <div class="col-sm-2"><label name="markValueStudentOne">
                    <?php if (isset($_SESSION['markValueStudentOne'])) {
                      echo $_SESSION['markValueStudentOne'];
                    } else {
                      echo "Mark not submitted!";
                    }
                    ?>
                  </label>
                </div>
                <div class="col-sm-5"></div>
              </div>
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><label> Comment: </label></div>
                <div class="col-sm-3"><label name="commentStudentOne">
                    <?php if (isset($_SESSION['commentStudentOne'])) {
                      echo $_SESSION['commentStudentOne'];
                    } else {
                      echo "Comment not submitted!";
                    }
                    ?>
                </div>
                <div class="col-sm-4"></div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group row content">
                <div class="col-sm-12"><label> Attachement Uploaded: </label></div>
              </div>
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <?php
                  if (isset($_SESSION['FirstImage'])) {
                    echo $_SESSION['FirstImage'];
                  } else {
                    echo "No Attachement uploaded!";
                  } ?>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="form-group row content">
            <div class="col-sm-5"></div>
            <div class="col-sm-3"><label> Second review </label></div>
            <div class="col-sm-3"></div>
          </div>
          <div class="form-group row content">
            <div class="col-sm-9">
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><label>Student ID:</label></div>
                <div class="col-sm-2">
                  <label name="IDStudentTwo">
                    <?php if (isset($_SESSION['IDStudentTwo'])) {
                      echo $_SESSION['IDStudentTwo'];
                    } else {
                      echo "nan!";
                    }
                    ?>
                  </label>
                </div>
                <div class="col-sm-5"></div>
              </div>
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><label>Mark Given:</label></div>
                <div class="col-sm-2"><label name="markValueStudentTwo">
                    <?php if (isset($_SESSION['markValueStudentTwo'])) {
                      echo $_SESSION['markValueStudentTwo'];
                    } else {
                      echo "Mark not submitted!";
                    }
                    ?>
                  </label>
                </div>
                <div class="col-sm-5"></div>
              </div>
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-5"><label> Comment: </label></div>
                <div class="col-sm-2"><label name="commentStudentTwo">
                    <?php if (isset($_SESSION['commentStudentTwo'])) {
                      echo $_SESSION['commentStudentTwo'];
                    } else {
                      echo "Comment not submitted!";
                    }
                    ?>
                </div>
                <div class="col-sm-5"></div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group row content">
                <div class="col-sm-12"><label> Attachement Uploaded: </label></div>
              </div>
              <div class="form-group row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                  <?php
                  if (isset($_SESSION['SecondImage'])) {
                    echo $_SESSION['SecondImage'];
                  } else {
                    echo "No Attachement uploaded!";
                  } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <div class="row text-center">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--   Footer-->
  <footer class="container-fluid text-center">
    <kbd>&copy; University of Greenwich</kbd>
  </footer>
  </div>
</body>

</html>