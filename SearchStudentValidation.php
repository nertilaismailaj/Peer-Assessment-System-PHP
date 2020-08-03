<?php
session_start();
include "Connection.php";
define('URLFORM', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/SearchStudent.php');
define('URLLIST', 'https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/SearchStudentValidation.php');
$referer = $_SERVER['HTTP_REFERER'];
// if rererrer is not the form redirect the browser to the form
if ($referer != URLFORM && $referer != URLLIST) {
    header('Location: ' . URLFORM);
}

$checkvalidation = array();
if (isset($_POST['searchButton'])) {


    if (empty($_POST['studentid'])) {
        $_SESSION['studentid'] = null;
    } else if (preg_match('/[0123456789]/', $_POST['studentid'])) {
        $studentid = $_POST['studentid'];
        $studentid = htmlentities($studentid);
        $studentid = mysqli_real_escape_string($connection, $studentid);
        $_SESSION['studentid'] = $studentid;
        $_SESSION['studentidVal'] = $studentid;
    }

    if (empty($_POST['markSearch'])) {
        $_SESSION['markSearch'] = null;
        $_SESSION['operationValue'] = null;
    } else if (preg_match('/[0123456789]/', $_POST['markSearch'])) {
        $markSearch = $_POST['markSearch'];
        $markSearch = htmlentities($markSearch);
        $markSearch = mysqli_real_escape_string($connection, $markSearch);
        $_SESSION['markSearch'] = $markSearch;
        $_SESSION['markSearchVal'] = $markSearch;
        $operationValue = $_POST['operationValue'];
        $_SESSION['operationValue'] = $operationValue;
        $sortingValue = $_POST['sortingValue'];
        $_SESSION['sortingValue'] = $sortingValue;
    }

    if (!empty($checkvalidation)) {
        foreach ($checkvalidation as $value) {
            $value;
        }
        unset($value);
    } else {
        $_SESSION['search'] = "yes";
        echo $_SESSION['search'];
        echo $_SESSION['operationValue'];
        echo $_SESSION['sortingValue'];
        echo $_SESSION['studentid'];
        echo $_SESSION['studentidVal'];
        echo $_SESSION['markSearch'];
        echo $_SESSION['markSearchVal'];
        header("location: SearchStudent.php");
        exit;
    }
}
