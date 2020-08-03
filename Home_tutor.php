<?php

include "SessionProperties.php";
include "Connection.php";
?>
<!--Start of html for tutor home page -->
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <title>PAS - Peer Assessment System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Adding Bootstrap to style the all page -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
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

        .tr {
            border-bottom: 1px solid #A9A9A9;
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
                    <a class="navbar-brand" style="color:Black;">Peer Marking System | University of Greenwich </a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Home_tutor.php"> <span class="glyphicon glyphicon-home"></span> Home </a></li>
                        <li><a href="https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/SearchStudent.php"> <span class="glyphicon glyphicon-search"></span> Search Review</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li> <a> Tutor Mode | <span class="glyphicon glyphicon-user"></span> <?php if (isset($_SESSION['login_user'])) {
                                                                                                    echo $_SESSION['login_user'];
                                                                                                } ?> </a></li>
                        <li>
                            <form class="navbar-form" method="post" action="Logout.php">
                                <button name="signOutButton" type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Body -->
        <div class="container">
            <p></p>
            <div class="panel  panel-default panelBackground">
                <div class="panel-heading">
                    <h4 align="center">Please see below all registered students.</h4>

                </div>
                <div class="panel-body" style="text-align: center">
                    <!--Enter table-->
                    <?php
                    $sql = "SELECT gt.Group_ID, ut.User_ID FROM mdb_ni8294f.Group_Table gt,mdb_ni8294f.User_Table ut  where gt.Group_ID  = ut.Group_ID and gt.Group_ID <> '0'";
                    $result = $connection->query($sql);
                    echo "<table class='table table-secondary table-hover'>";
                    if ($result->num_rows > 0) {
                        echo "<thead>
                                <tr>
                                    <td>Group ID</td>
                                    <td>Members</td>
                                    <td>Review status</td>
                                    <td>Email</td>
                                </tr>
                             </thead>";
                        //output data here
                        while ($row = $result->fetch_assoc()) {
                            if ($previousValueOne == $row["Group_ID"]) {
                                echo " <tr>
                                <td><a href='https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/GetStudent.php?User_ID=" . $row["User_ID"] . "'>" . $row["User_ID"] . "</td>
                              </tr>";
                                echo "<script>console.log('line108');</script>";
                            } else {
                                echo "<tbody>
                                    <tr>
                                        <td rowspan='3'>" . $row["Group_ID"] . "</td>
                                        <td><a href='https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/GetStudent.php?User_ID=" . $row["User_ID"] . "'>" . $row["User_ID"] . "</td>";

                                $GroupID = $row["Group_ID"];
                                $ssql = "SELECT * FROM Peer_Mark_Table WHERE Group_ID = '$GroupID' AND Submitted = '1'";
                                $results = $connection->query($ssql);
                                $counts = mysqli_num_rows($results);

                                if ($counts !== 6) {
                                    echo "<td rowspan='3'>Not Completed</td>
                                          <td rowspan='3'>";
                                    ?>
                                    <form method="post" action="mailList.php" onsubmit="return confirm ('Ready to send reminder email?')">
                                        <input type="hidden" name="group" id="group" value="<?php echo $row["Group_ID"]; ?>" />
                                        <button name='sumbitReminder' type='submit' class='btn btn-default'>Send Reminder</button>
                                    </form>

                                <?php
                                                echo "</td>";
                                            } else {
                                                echo "<td rowspan='3'>Completed</td>
                                          <td rowspan='3'>";
                                                ?>

                                    <form method="post" action="mailList.php" onsubmit="return confirm ('Ready to send review with grades?')">
                                        <input type="hidden" name="group" id="group" value="<?php echo $row["Group_ID"]; ?>" />
                                        <button name='sumbitReview' type='submit' class='btn btn-default'>Send Review</button>
                                    </form>

                    <?php
                                    echo "</tr>";
                                }

                                //first member of the group
                                $previousValueOne = $row["Group_ID"];
                            }
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "0 results";
                    }


                    ?>
                </div>

            </div>

        </div>
        <!--   Footer-->
        <footer class="container-fluid text-center">
            <kbd>&copy; University of Greenwich</kbd>
        </footer>
    </div>
</body>

</html>