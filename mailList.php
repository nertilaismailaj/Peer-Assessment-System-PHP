<?php
session_start();
include "Connection.php";
function sendEmail($email, $GroupID, $Body)
{
    $subject = 'Peer Assessment Reminder';
    $message = "
        <html>
        <h2 style=\"text-align: center;\">Peer Assessment Review</h2></html>
        <p style=\"text-align: center;\">Hello, Members of Group " . $GroupID . "

        <br />
        " . $Body . "</p>

        <p style=\"text-align: center;\">&nbsp;</p>
        <p style=\"text-align: center;\">Univeristy of Greenwich | Peer Assessment System</p>
        <p style=\"text-align: center;\">This is an automated response, please DO NOT reply.</p>
        <p style=\"text-align: center;\">&nbsp;</p>
        </html>
        ";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: noreply@gre.ac.uk\r\n";

    mail($email, $subject, $message, $headers);
    header("location:Home_tutor.php");
}
if (isset($_POST['sumbitReview'])) {
    $GroupID = $_POST['group'];
    $sql = "SELECT User_ID,Email_Address,Overall_Grade FROM mdb_ni8294f.User_Table where Group_ID = '$GroupID'";
    $result = $connection->query($sql);

    $studentEmails = array();
    $studentids = array();
    $studentGrades = array();
    while ($rows = $result->fetch_assoc()) {
        $studentEmails[] = $rows['Email_Address'];
        $studentids[] = $rows['User_ID'];
        $studentGrades[] = $rows['Overall_Grade'];
    }

    $email =  $studentEmails[0] . ', ' .  $studentEmails[1] . ', ' . $studentEmails[2];
    $Body = "Find your overall grades below: <br/>
    <p style=\"text-align: center;\"> " . $studentids[0] . " : " . $studentGrades[0] . " </p><br/>
    <p style=\"text-align: center;\"> " . $studentids[1] . " : " . $studentGrades[1] . " </p><br/>
    <p style=\"text-align: center;\"> " . $studentids[2] . " : " . $studentGrades[2] . " </p><br/>";

    sendEmail($email, $GroupID, $Body);
}

if (isset($_POST['sumbitReminder'])) {

    $GroupID = $_POST['group'];
    $sql = "SELECT Email_Address FROM mdb_ni8294f.User_Table where Group_ID = '$GroupID'";
    $result = $connection->query($sql);

    $studentEmails = array();
    while ($rows = $result->fetch_assoc()) {
        $studentEmails[] = $rows['Email_Address'];
    }

    $email =  $studentEmails[0] . ', ' .  $studentEmails[1] . ', ' . $studentEmails[2];
    $Body = "Please complete your peer review.";
    sendEmail($email, $GroupID, $Body);
}
