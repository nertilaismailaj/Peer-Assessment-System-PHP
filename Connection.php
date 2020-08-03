<?php
$host = "mysql.cms.gre.ac.uk";
$username = "ni8294f";
$password = "Nertila97";

//https://stumyadmin.cms.gre.ac.uk/
// Create connection
$connection = new mysqli($host, $username, $password);

// Check connection
if ($connection->connect_error) {
    die("Connection failed Nertila: " . $connection->connect_error);
    return false;
}
