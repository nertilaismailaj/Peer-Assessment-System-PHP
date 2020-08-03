<?php
session_start();
//https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Index.php
//check what type of session is logged on
if (!empty($_SESSION['login_user'])) {
    $IDnoCheck = $_SESSION['login_user'];
    if ($IDnoCheck == "0000000000") {
        header("location: Home_tutor.php");
        exit;
    } else {
        header("location: Home_student.php");
        exit;
    }
}
?>

<!--Start of html for index page -->
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <title>PAS - Peer Assessment System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" content="ni8294f@gre.ac.uk" />
    <!--Adding Bootstrap to style the all page -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        //allow only numbers entered from 000000000 to 999999999
        function checkNumber(x) {
            x = (x) ? x : window.event;
            var charValue = (x.which) ? x.which : x.keyCode;
            if ((charValue < 48 || charValue > 57)) {
                return false;
            }
        }
    </script>
    <style>
        /* Set background image */
        body {
            /* The image used */
            background-image: url("images/baground_index.jpg");
            /* Center and scale the image nicely */
            background-position: top;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Set create account panel opacity */
        .panelBackground {
            background: rgba(255, 255, 255, .6);
        }
    </style>
</head>

<body class="bg">
    <div class="container-fluid">
        <!--Beginning of the navigation bar section-->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="http://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Index.php" style="color:Black;">Peer Marking System | University of Greenwich</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <!-- Beginning of the login section-->
                            <form class="navbar-form navbar-right" method="post" action="Login.php">
                                <div class="form-group">
                                    <input name="signInNineDigitID" type="text" class="form-control" placeholder="Student ID NO: 000xxxxxx" onkeypress="return checkNumber(event)" maxlength="9" Value="<?php if (isset($_COOKIE["NineDigitID"])) {
                                                                                                                                                                                                            echo $_COOKIE["NineDigitID"];
                                                                                                                                                                                                        } ?>" />
                                    <input name="signInPassword" type="password" class="form-control" placeholder="Password" />
                                    <?php
                                    if (!empty($_SESSION['invalidLoginLabel'])) {
                                        echo $_SESSION['invalidLoginLabel'];
                                    }
                                    unset($_SESSION['invalidLoginLabel']);
                                    ?>
                                </div>
                                <button name="signInButton" type="submit" class="btn btn-default">Sign In</button>
                            </form>
                            <!-- End of Login section-->
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End of nav bar -->
        <!-- Beginning of main content -->
        <div class="row container-fluid text-center">
            <div class="col-xs-12  col-sm-12 col-md-7 col-lg-8">
                <!-- Empty main content -->
            </div>
            <!--Registration section-->
            <div class="col-xs-12  col-sm-12 col-md-5 col-lg-4">
                <div class="panel panel-default panelBackground">
                    <div class="panel-body text-center">
                        <h1>Create Account</h1>
                        <form method="post" action="Register.php">
                            <!--UserID-->
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label for="nineDigitID">Student ID NO:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="nineDigitID" id="nineDigitID" type="text" class="form-control" minlength="9" maxlength="9" onkeypress="return checkNumber(event)" maxlength="9" value="<?php if (isset($_SESSION['nineDigitIDValidated'])) {
                                                                                                                                                                                                            echo $_SESSION['nineDigitIDValidated'];
                                                                                                                                                                                                        }
                                                                                                                                                                                                        unset($_SESSION['nineDigitIDValidated']);
                                                                                                                                                                                                        ?>" placeholder="Student ID NO: 000xxxxxx" required />
                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if (!empty($_SESSION['nineDigitIDLabel'])) {
                                        echo $_SESSION['nineDigitIDLabel'];
                                    }
                                    unset($_SESSION['nineDigitIDLabel']);
                                    ?>
                                </div>
                            </div>
                            <!--Email-->
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label>Email:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="studentEmail" type="email" class="form-control" value="<?php
                                                                                                        if (isset($_SESSION['emailValidated'])) {
                                                                                                            echo $_SESSION['emailValidated'];
                                                                                                        }
                                                                                                        unset($_SESSION['emailValidated']); ?>" placeholder="mail@gre.ac.uk" required />
                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if (!empty($_SESSION['emailLabel'])) {
                                        echo $_SESSION['emailLabel'];
                                    }
                                    unset($_SESSION['emailLabel']);
                                    ?>
                                </div>
                            </div>
                            <!--Password-->
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label>Password:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="studentPassword" type="password" class="form-control" placeholder="Password" minlength="8" maxlength="10" required />
                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if (!empty($_SESSION['passwordLabel'])) {
                                        echo $_SESSION['passwordLabel'];
                                    }
                                    unset($_SESSION['passwordLabel']);
                                    ?>
                                </div>
                            </div>
                            <!--Confirm Password-->
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label>Confirm Password:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="studentConfirmPassword" type="password" class="form-control" placeholder="Confirm Password" minlength="8" maxlength="10" required />
                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if (!empty($_SESSION['confirmPasswordLabel'])) {
                                        echo $_SESSION['confirmPasswordLabel'];
                                    }
                                    unset($_SESSION['confirmPasswordLabel']);
                                    ?>
                                </div>
                            </div>
                            <!-- Select Group number -->
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label>Group selection:</label>
                                </div>
                                <div class="col-sm-6">
                                    <?php
                                    include "Connection.php";
                                    $sql = "select gt.Group_ID from mdb_ni8294f.Group_Table gt where gt.Group_ID <> '0' and gt.Member_Count < 3";
                                    $result = $connection->query($sql);
                                    ?>
                                    <select name="groupID" class="form-control" required>
                                        <?php
                                        echo "<option> Select Group...</option>";
                                        while ($rows = $result->fetch_assoc()) {
                                            $group_id = $rows['Group_ID'];
                                            if ($group_id == $_SESSION['groupValidated']) {
                                                echo "<option value='$group_id' selected>$group_id</option>";
                                            } else {
                                                echo "<option value='$group_id'>$group_id</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if (!empty($_SESSION['GroupIDLabel'])) {
                                        echo $_SESSION['GroupIDLabel'];
                                    }
                                    unset($_SESSION['GroupIDLabel']);
                                    ?>
                                </div>
                            </div>
                            <!-- Captcha -->
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <br />
                                    <br />
                                    <label>Captcha:</label>
                                </div>
                                <div class="col-sm-6">
                                    <img src="Captcha.php" alt="Captcha" />
                                    <!-- <div class="well well-sm "><img src="Captcha.php" alt="Captcha" /></div> -->
                                    <br />
                                    <br />
                                    <input type="text" class="form-control" placeholder="Enter Captcha" name="studentCaptcha" required />
                                </div>
                                <div class="col-sm-3">
                                    <br />
                                    <br />
                                    <?php
                                    if (!empty($_SESSION['CaptchaLabel'])) {
                                        echo $_SESSION['CaptchaLabel'];
                                    }
                                    unset($_SESSION['CaptchaLabel']);
                                    ?>
                                </div>
                            </div>
                            <!-- Register button -->
                            <div class="form-group">
                                <button name="registerButton" type="submit" class="btn btn-default">Register</button>
                            </div>
                        </form>
                        <!-- End of registration form-->
                    </div>
                </div>
            </div>
        </div>
        <!-- End of middle section-->
        <!--   Footer-->
        <footer class="container-fluid text-center">
            <kbd>&copy; University Of Greenwich</kbd>
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <strong>Welcome to University of Greenwich peer assessment system. By using this site you agree to our cookies.</strong>
            </div>
        </footer>
    </div>
</body>

</html>