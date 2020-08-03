<?php
define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Index.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Register.php');
$referer = $_SERVER['HTTP_REFERER'];
// if rererrer is not the form redirect the browser to the form
if ($referer != URLFORM && $referer != URLLIST) {
    header('Location: ' . URLFORM);
}

//Check for session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//sql connection file
include "Connection.php";
$checkvalidation = array();

if (isset($_POST['registerButton'])) {

    //handle student id
    if (preg_match('/[0123456789]/', $_POST['nineDigitID'])) {
        $IDnoCheck = $_POST['nineDigitID'];
        $_SESSION['nineDigitIDValidated'] = $_POST['nineDigitID'];
        $IDnoCheck = htmlentities($IDnoCheck);
        $IDnoCheck = mysqli_real_escape_string($connection, $IDnoCheck);
        //check if the valid id exists
        $sql = "SELECT User_ID FROM mdb_ni8294f.User_Table WHERE BINARY User_ID='$IDnoCheck'";
        $results = $connection->query($sql);
        $rows = mysqli_num_rows($results);

        if ($rows == 1) {
            $_SESSION['nineDigitIDLabel'] = '<span class="label label-danger">ID no already exists!</span>';
            array_push($checkvalidation, $_SESSION['nineDigitIDLabel']);
        } else {
            $nineDigits = $_POST['nineDigitID'];
            $nineDigits = htmlentities($nineDigits);
            $nineDigits = mysqli_real_escape_string($connection, $nineDigits);
        }
    } else {
        $_SESSION['nineDigitIDLabel'] = '<span class="label label-danger">Non valid Student ID NO!</span>';
        array_push($checkvalidation, $_SESSION['nineDigitIDLabel']);
    }

    //handle student email
    if (preg_match('/^([\w\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})$/', $_POST['studentEmail'])) {
        $email = $_POST['studentEmail'];
        $email = htmlentities($email);
        $email = mysqli_real_escape_string($connection, $email);
        $_SESSION['emailValidated'] = $email;
    } else {
        $_SESSION['emailValidated'] = $_POST['studentEmail'];
        $_SESSION['emailLabel'] = '<span class="label label-danger">Email not valid!</span>';
        array_push($checkvalidation, $_SESSION['emailLabel']);
    }

    //handle student password
    if (empty($_POST['studentPassword'])) {
        $_SESSION['passwordLabel'] = '<span class="label label-danger">Please enter Password!</span>';
        array_push($checkvalidation, $_SESSION['passwordLabel']);
    } else {
        $password = $_POST['studentPassword'];
        $password_encrypted = md5($password); //encrypt password
        $password_encrypted = htmlentities($password_encrypted);
        $password_encrypted = mysqli_real_escape_string($connection, $password_encrypted);
    }
    //handle student confirm password field
    if (empty($_POST['studentConfirmPassword'])) {
        $_SESSION['confirmPasswordLabel'] = '<span class="label label-danger">Please confirm Password!</span>';
        array_push($checkvalidation, $_SESSION['confirmPasswordLabel']);
    } else {
        $confpasswords = $_POST['studentConfirmPassword'];
        $confpasswords_encrypted = md5($confpasswords);
        $confpasswords_encrypted = htmlentities($confpasswords_encrypted);
        $confpasswords_encrypted = mysqli_real_escape_string($connection, $confpasswords_encrypted);
    }

    //handle password matching
    if ($password_encrypted != $confpasswords_encrypted) {
        $_SESSION['confirmPasswordLabel'] = '<span class="label label-danger">Passwords does not match!</span>';
        array_push($checkvalidation, $_SESSION['confirmPasswordLabel']);
    }

    if ($_POST['groupID'] == "Select Group...") {
        $_SESSION['groupValidated'] =  $_POST['groupID'];
        $_SESSION['GroupIDLabel'] = '<span class="label label-danger">Please Select Group!</span>';
        array_push($checkvalidation, $_SESSION['GroupIDLabel']);
    } else {
        $group_id = $_POST['groupID'];
        $group_id = htmlentities($group_id);
        $group_id = mysqli_real_escape_string($connection, $group_id);
        $_SESSION['groupValidated'] = $group_id;
    }
    //handle Captcha entered
    if (empty($_POST['studentCaptcha'])) {

        $_SESSION['CaptchaLabel'] = '<span class="label label-danger">Confirm Captcha!</span>';
        array_push($checkvalidation, $_SESSION['CaptchaLabel']);
    } else if ($_SESSION['custom_captcha'] != $_POST['studentCaptcha']) {

        $_SESSION['CaptchaLabel'] = '<span class="label label-danger">Wrong Captcha!</span>';
        array_push($checkvalidation, $_SESSION['CaptchaLabel']);
    } else {
        $captcha = $_POST['studentCaptcha'];
    }

    if (empty($checkvalidation)) {
        //insert values in user table
        $sql = "INSERT into mdb_ni8294f.User_Table(User_ID,Password,Email_Address,Group_ID,Tutor) VALUES ('$nineDigits','$password_encrypted','$email','$group_id','0')";
        if ($connection->query($sql) === true) {
            //increment the member count in Group table
            $sql = "select Member_Count from mdb_ni8294f.Group_Table where Group_ID = '$group_id'";
            $results = $connection->query($sql);
            $count = mysqli_num_rows($results);
            if ($count == 1) {
                while ($row = $results->fetch_assoc()) {
                    $m_count = $row["Member_Count"];
                }
                echo $m_count;
                if ($m_count < 3) {
                    $m_count++;
                }

                $sql = "UPDATE mdb_ni8294f.Group_Table SET Member_Count = '$m_count' WHERE Group_ID = '$group_id'";
                if ($connection->query($sql) === true) {
                    $_SESSION['login_user'] = $nineDigits;
                    header("location: Home_student.php");
                } else {
                    echo "<br> 1.Member_Count Row not inserted $sql." . $mysqli->error;
                }
            } else {
                echo "<br> 2.Member_Count Row not selected $sql." . $mysqli->error;
            }
        } else {
            $connection->close();
            header("location:Index.php");
            echo "<br> 3.Row not inserted $sql." . $mysqli->error;
            exit;
        }
    } else {

        foreach ($checkvalidation as $value) {
            $value;
            header("location:Index.php");
        }
        var_dump($value);
        var_dump($checkvalidation);
        unset($value);
    }
}
