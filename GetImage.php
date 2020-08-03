<?php
include "Connection.php";

$sql = 'SELECT Image,Type FROM mdb_ni8294f.Peer_Mark_Table WHERE Peer_Mark_ID="' . $_GET['Peer_Mark_ID'] . '"';
$result = $connection->query($sql);
$row = mysqli_fetch_assoc($result);

if ($row['Image'] != null) {
    header('Content-Type: ' . $row['Type']);
    echo $row['Image'];
}
