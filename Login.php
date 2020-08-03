<?php
define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Index.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Login.php');
$referer = $_SERVER['HTTP_REFERER'];
// if rererrer is not the form redirect the browser to the form
if ($referer != URLFORM && $referer != URLLIST) {
    header('Location: ' . URLFORM);
}
session_start();
include "Connection.php";

//Checking if the request is coming from Signin button of Index.php
if (isset($_POST['signInButton'])) {
    //check if any of the field is empty
    if (empty($_POST['signInNineDigitID']) || empty($_POST['signInPassword'])) {
        $_SESSION['invalidLoginLabel'] = '<span class="label label-danger center-block">Please fill both fields!</span>';
        header("location:Index.php");
    } else {
        // Define $NineDigitID and $password
        $NineDigitID = $_POST['signInNineDigitID'];
        $NineDigitID = htmlentities($NineDigitID);
        $NineDigitID = mysqli_real_escape_string($connection, $NineDigitID);

        $PassWord = $_POST['signInPassword'];
        $PassWord = htmlentities($PassWord);
        $PassWord = mysqli_real_escape_string($connection, $PassWord);
        $PassWord = md5($PassWord);

        $sql = "SELECT * FROM mdb_ni8294f.User_Table WHERE BINARY User_ID='$NineDigitID' AND BINARY Password='$PassWord'";

        $results = $connection->query($sql);
        $rows = mysqli_num_rows($results);

        if ($rows == 1) {
            $row = mysqli_fetch_array($results);
            $_SESSION['login_user'] = $row['User_ID']; // Initializing Session

            if ($NineDigitID == '000000000') { //check if the user is a tutor or student
                header("location: Home_tutor.php"); //redirecting to tutor's homepage
                setcookie("Tutor", $_SESSION['login_user'], time() + (86400 * 30), "/");
            } else {
                header("location: Home_student.php"); //redirecting to student's homepages
                setcookie("Student", $_SESSION['login_user'], time() + (86400 * 30), "/");
            }
        } else {
            $_SESSION['invalidLoginLabel'] = '<span class="label label-danger center-block">ID NO or Password is invalid!</span>';
            header("location:Index.php");
        }
        /* close connection */
        $connection->close();
    }
}
