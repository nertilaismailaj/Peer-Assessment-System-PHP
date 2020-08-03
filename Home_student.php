<?php
include "Connection.php";
include "LoadSavedData.php";
?>
<!--Start of html for student home page -->
<!DOCTYPE html>
<html lang="en-GB">

<head>
  <title>PAS - Peer Assessment System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script>
    //allow only numbers entered from 1 to 10
    function checkNumber(x) {
      x = (x) ? x : window.event;
      var charValue = (x.which) ? x.which : x.keyCode;
      if ((charValue < 48 || charValue > 57)) {
        return false;
      }
    }
  </script>

  <link rel="stylesheet" href="css/css.css">
  <style>
    body {
      /* The image used */
      background-image: url("images/baground_index.jpg");
      /* Center and scale the image nicely */
      background-position: top;
      background-repeat: no-repeat;
      background-size: cover;
    }

    .panelBackground {
      background: rgba(255, 255, 255, .9);
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
          <a class="navbar-brand" href="http://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Home_student.php" style="color:Black;">Peer Marking System | University of Greenwich</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="">Student <span class="glyphicon glyphicon-user"></span> :
                <?php if (isset($_SESSION['login_user'])) {
                  echo $_SESSION['login_user'];
                } ?> |
                Group :
                <?php if (isset($_SESSION['group_id'])) {
                  echo $_SESSION['group_id'];
                } ?>
              </a></li>
            <li>
              <form class="navbar-form" method="post" action="Logout.php">
                <button name="signOutButton" type="submit" class="btn btn-default">
                  <span class="glyphicon glyphicon-log-out"></span>Sign out
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!--Body of the page -->
    <div class="container">
      <p></p>
      <div class="panel-group" id="accordion">
        <!-- First -->
        <div class="panel panel-default panelBackground">
          <div class="panel-heading">
            <h4 class="panel-title text-center">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Student:
                <?php if (isset($_SESSION['labelStudentOne'])) {
                  echo $_SESSION['labelStudentOne'];
                } else {
                  echo "No one registered yet!";
                }
                ?>
                <?php if (isset($_SESSION['FirstSubmitted'])) { ?>
                  <span class="glyphicon glyphicon-thumbs-up"></span>
                <?php } else {  ?>
                  <span class="glyphicon glyphicon-thumbs-down"></span>
                <?php }  ?>
              </a>
            </h4>
          </div>

          <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body text-center">
              <!--Evaluate Student nr 1 -->
              <form method="post" action="MarkedStudentOne.php" enctype="multipart/form-data">

                <!--Icon Student nr 1 -->
                <div class="form-group row content">
                  <div class="col-sm-5"></div>
                  <div class="col-sm-3">
                    <img class="img-responsive" src="images/userOne.png" alt="user_icon" width="150" height="150">
                  </div>
                  <div class="col-sm-4"></div>
                </div>

                <!--ID label Student nr 1 -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label for="labelStudentOne">Evaluate student with ID NO:</label>
                  </div>
                  <div class="col-sm-6">
                    <label name="labelStudentOne">
                      <?php if (isset($_SESSION['labelStudentOne'])) {
                        echo $_SESSION['labelStudentOne'];
                      } else {
                        echo "No other student part of this group!";
                      }
                      ?>
                    </label>
                  </div>
                  <div class="col-sm-3"></div>
                </div>

                <!-- Mark Student nr 1 -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label for="markValue">Mark:</label>
                  </div>
                  <div class="col-sm-6">
                    <input name="markValueStudentOne" id="markValueStudentOne" type="number" max="10" min="0" class="form-control" onkeypress="return checkNumber(event)" value="<?php
                                                                                                                                                                                  if (isset($_SESSION['markValueStudentOneValidated'])) {
                                                                                                                                                                                    echo $_SESSION['markValueStudentOneValidated'];
                                                                                                                                                                                  }
                                                                                                                                                                                  unset($_SESSION['markValueStudentOneValidated']); ?>" required />
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['markValueStudentOneLabel'])) {
                      echo $_SESSION['markValueStudentOneLabel'];
                    }
                    unset($_SESSION['markValueStudentOneLabel']); ?>
                  </div>
                </div>

                <!--Comment Student nr 1 -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label for="commentStudentOne">Comment:</label>
                  </div>
                  <div class="col-sm-6">
                    <input name="commentStudentOne" id="commentStudentOne" type="text" class="form-control" placeholder="e.g., this group member did not show up in the meetings, missed his/her assigned deliverables, etc" maxlength="250" value="<?php
                                                                                                                                                                                                                                                    if (isset($_SESSION['commentStudentOneValidated'])) {
                                                                                                                                                                                                                                                      echo $_SESSION['commentStudentOneValidated'];
                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                    unset($_SESSION['commentStudentOneValidated']); ?>" required />
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['commentStudentOneLabel'])) {
                      echo $_SESSION['commentStudentOneLabel'];
                    }
                    unset($_SESSION['commentStudentOneLabel']); ?>
                  </div>
                </div>

                <!--Upload section Student nr 1  -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label class="custom-file-label" for="StudentOneFile">Attachment: </label>
                  </div>
                  <div class="col-sm-6">
                    <?php if (isset($_SESSION['FirstSubmitted']) || !isset($_SESSION['labelStudentOne'])) { ?>
                      <label class="custom-file-label"> Cannot upload Attachment! </label>
                    <?php } else { ?>
                      <input type="file" size="40" name="StudentOneFile" id="StudentOneFile" class="custom-file-input" />
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['imageStudentOneLabel'])) {
                      echo $_SESSION['imageStudentOneLabel'];
                    }
                    unset($_SESSION['imageStudentOneLabel']); ?>
                  </div>
                </div>

                <!--Image checking  -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <?php if (!empty($_SESSION['FirstImage'])) { ?>
                      <label class="custom-file-label"> Attachment Uploaded: </label>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['FirstImage'])) {
                      echo $_SESSION['FirstImage'];
                    }
                    unset($_SESSION['FirstImage']); ?>
                  </div>
                  <div class="col-sm-6"></div>
                </div>

                <!--Button section Student nr 1 -->
                <div class="form-group row content">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-6">
                    <button name="saveButtonStudentOne" type="submit" class="btn btn-info" <?php if (isset($_SESSION['FirstSubmitted']) || !isset($_SESSION['labelStudentOne'])) { ?> disabled <?php } ?>>Save</button>
                    <button name="deleteButtonStudentOne" type="submit" class="btn btn-danger" <?php if (isset($_SESSION['FirstSubmitted']) || !isset($_SESSION['labelStudentOne'])) { ?> disabled <?php } ?>>Delete</button>
                    <button name="sumbitButtonStudentOne" type="submit" class="btn btn-success" <?php if (isset($_SESSION['FirstSubmitted']) || !isset($_SESSION['labelStudentOne'])) { ?> disabled <?php } ?>>Submit</button>
                  </div>
                  <div class="col-sm-3"></div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- First -->
        <div class="panel panel-default panelBackground">
          <div class="panel-heading">
            <h4 class="panel-title text-center">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Student:
                <?php if (isset($_SESSION['labelStudentTwo'])) {
                  echo $_SESSION['labelStudentTwo'];
                } else {
                  echo "No one registered yet!";
                }
                ?>
                <?php if (isset($_SESSION['SecondSubmitted'])) { ?>
                  <span class="glyphicon glyphicon-thumbs-up"></span>
                <?php } else {  ?>
                  <span class="glyphicon glyphicon-thumbs-down"></span>
                <?php }  ?>

              </a>
            </h4>
          </div>

          <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body  text-center">
              <!--Evaluate Student nr 2 -->
              <form method="post" action="MarkedStudentTwo.php" enctype="multipart/form-data">
                <!--Icon Student nr 2 -->
                <div class="form-group row content">
                  <div class="col-sm-5"></div>
                  <div class="col-sm-3">
                    <img class="img-responsive" src="images/userTwo.png" alt="user_icon" width="150" height="150">
                  </div>
                  <div class="col-sm-4"></div>
                </div>
                <!--ID Label Student nr 2 -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label for="labelStudentTwo">Evaluate student with ID NO:</label>
                  </div>
                  <div class="col-sm-6">
                    <?php if (isset($_SESSION['labelStudentTwo'])) {
                      echo $_SESSION['labelStudentTwo'];
                    } else {
                      echo "No other student part of this group!";
                    }
                    ?>
                  </div>
                  <div class="col-sm-3"></div>
                </div>
                <!--Mark Student nr 2 -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label for="markValue">Mark:</label>
                  </div>
                  <div class="col-sm-6">
                    <input name="markValueStudentTwo" id="markValueStudentTwo" type="number" max="10" min="0" class="form-control" onkeypress="return checkNumber(event)" value="<?php
                                                                                                                                                                                  if (isset($_SESSION['markValueStudentTwoValidated'])) {
                                                                                                                                                                                    echo $_SESSION['markValueStudentTwoValidated'];
                                                                                                                                                                                  }
                                                                                                                                                                                  unset($_SESSION['markValueStudentTwoValidated']);
                                                                                                                                                                                  ?>" required />
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['markValueStudentTwoLabel'])) {
                      echo $_SESSION['markValueStudentTwoLabel'];
                    }
                    unset($_SESSION['markValueStudentTwoLabel']); ?>
                  </div>
                </div>
                <!--Comment Student nr 2 -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label for="commentStudentTwo">Comment:</label>
                  </div>
                  <div class="col-sm-6">
                    <input name="commentStudentTwo" id="commentStudentTwo" type="text" class="form-control" placeholder="e.g., this group member did not show up in the meetings, missed his/her assigned deliverables, etc" maxlength="250" value="<?php
                                                                                                                                                                                                                                                    if (isset($_SESSION['commentStudentTwoValidated'])) {
                                                                                                                                                                                                                                                      echo $_SESSION['commentStudentTwoValidated'];
                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                    unset($_SESSION['commentStudentTwoValidated']); ?>" required />
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['commentStudentTwoLabel'])) {
                      echo $_SESSION['commentStudentTwoLabel'];
                    }
                    unset($_SESSION['commentStudentTwoLabel']); ?>
                  </div>
                </div>

                <!--Upload section Student nr 1  -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <label class="custom-file-label" for="StudentTwoFile">Attachment: </label>
                  </div>
                  <div class="col-sm-6">
                    <?php if (isset($_SESSION['SecondSubmitted']) || !isset($_SESSION['labelStudentTwo'])) { ?>
                      <label class="custom-file-label"> Cannot upload Attachment! </label>
                    <?php } else { ?>
                      <input type="file" size="40" name="StudentTwoFile" class="custom-file-input" />
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['imageStudentTwoLabel'])) {
                      echo $_SESSION['imageStudentTwoLabel'];
                    }
                    unset($_SESSION['imageStudentTwoLabel']); ?>
                  </div>
                </div>

                <!--Image checking  -->
                <div class="form-group row content">
                  <div class="col-sm-3">
                    <?php if (!empty($_SESSION['SecondImage'])) { ?>
                      <label class="custom-file-label"> Attachment Uploaded: </label>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <?php
                    if (!empty($_SESSION['SecondImage'])) {
                      echo $_SESSION['SecondImage'];
                    }
                    unset($_SESSION['SecondImage']); ?>
                  </div>
                  <div class="col-sm-6"></div>
                </div>


                <!--Button section Student nr 2 -->
                <div class="form-group row content">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-6">
                    <button name="saveButtonStudentTwo" type="submit" class="btn btn-info" <?php if (isset($_SESSION['SecondSubmitted']) || !isset($_SESSION['labelStudentTwo'])) { ?> disabled <?php } ?>>Save</button>
                    <button name="deleteButtonStudentTwo" type="submit" class="btn btn-danger" <?php if (isset($_SESSION['SecondSubmitted']) || !isset($_SESSION['labelStudentTwo'])) { ?> disabled <?php } ?>>Delete</button>
                    <button name="sumbitButtonStudentTwo" type="submit" class="btn btn-success" <?php if (isset($_SESSION['SecondSubmitted']) || !isset($_SESSION['labelStudentTwo'])) { ?> disabled <?php } ?>>Submit</button>
                  </div>
                  <div class="col-sm-3"></div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--   Footer-->
    <footer class="container-fluid text-center">
      <kbd>&copy; University of Greenwich</kbd>
    </footer>
</body>

</html>