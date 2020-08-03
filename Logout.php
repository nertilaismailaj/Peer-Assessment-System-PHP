<?php
session_start();
//destroy session and cookie and go back to main site
if (isset($_COOKIE['Tutor'])) {
    setcookie("Tutor", $_SESSION['login_user'], time() - 7000000, "/");
}
if (isset($_COOKIE['Student'])) {
    setcookie("Student", $_SESSION['login_user'], time() - 7000000, "/");
}

if (session_destroy()) {
    header("Location: Index.php");
}
