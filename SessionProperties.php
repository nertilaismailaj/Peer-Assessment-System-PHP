<?php
session_start();

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if (empty($_SESSION['login_user'])) {
    header("location: Index.php");
    exit;
}
include "Connection.php";
$IDnoCheck = $_SESSION['login_user'];
unset($_SESSION['login_user']);
$sql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID='$IDnoCheck'";
$result = $connection->query($sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    $row = mysqli_fetch_array($result);
    $_SESSION['login_user'] = $row['User_ID'];
    $_SESSION['group_id'] = $row['Group_ID'];
} else {
    header("location: index.php");
}
