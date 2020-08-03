<?php

include "SessionProperties.php";
include "Connection.php";

$rekord_per_page = 5;

//check which page using by GET
if (isset($_GET["page"])) {
    $page  = $_GET["page"];
} else {
    $page = 1;    //if get page not set, set it to 1
}

$studentID = $_SESSION['studentid'];
$markSearch = $_SESSION['markSearch'];

if (isset($_SESSION['studentid'])) {
    $studentid_cookie = setcookie('studentid_cookie', $studentID, time() + (86400 * 30), "/");
}

if (isset($_SESSION['markSearch'])) {
    $markSearch_cookie = setcookie('markSearch_cookie', $markSearch, time() + (86400 * 30), "/");
}

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
    <script type="text/javascript">
        //allow only numbers entered from 1 to 10
        function checkNumber(x) {
            x = (x) ? x : window.event;
            var charValue = (x.which) ? x.which : x.keyCode;
            if ((charValue < 48 || charValue > 57)) {
                return false;
            }
        }
    </script>
    <style>
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
                        <li><a href="https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/Home_tutor.php"> <span class="glyphicon glyphicon-home"></span> Home </a></li>
                        <li class="active"><a href="https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/SearchStudent.php"> <span class="glyphicon glyphicon-search"></span> Search Review</a></li>
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


        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 align="center">Student Search Form</h4>
                    </div>
                    <div class="panel-body">
                        <form name="form" method="post" action="SearchStudentValidation.php" onsubmit=" return storedCookies(this);">
                            <div class="row text-center form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-3">
                                    <label for="name" class="control-label"> Student ID: </label>
                                </div>
                                <div class="col-sm-2">
                                    <input name="studentid" type="text" class="form-control" placeholder="000xxxxxx" onkeypress="return checkNumber(event)" maxlength="9" value="<?php echo $studentID; ?>" />
                                </div>
                                <div class="col-sm-5">
                                </div>
                            </div>
                            <!-- mark field -->
                            <div class="row text-center form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-3">
                                    <label for="name" class="control-label"> Overall numeric grade: </label>
                                </div>
                                <div class="col-sm-2">
                                    <select name="operationValue" class="form-control">
                                        <option value="<">
                                            <</option> <option value="<=">
                                                <=</option> <option value="=" selected>=
                                        </option>
                                        <option value=">">></option>
                                        <option value=">=">>=</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input name="markSearch" type="number" max="10" min="0" class="form-control" onkeypress="return checkNumber(event)" value="<?php echo $markSearch; ?>" />
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                            <div class="row text-center form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2">
                                    <select name="sortingValue" class="form-control">
                                        <option value="ORDER BY User_ID ASC">Sort ASC User ID</option>
                                        <option value="ORDER BY User_ID DESC">Sort DESC User ID</option>
                                        <option value="ORDER BY Overall_Grade ASC">Sort ASC Overall Grade</option>
                                        <option value="ORDER BY Overall_Grade DESC">Sort DESC Overall Grade</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button name="searchButton" type="submit" class="btn btn-default"> <span class="glyphicon glyphicon-search"></span> Search </button>
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Search result row -->
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 align="center">Search Results</h4>
                    </div>
                    <?php if (isset($_SESSION['search'])) { ?>
                        <div class="panel-body">
                            <!--Table row -->
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="row text-center col-sm-6">
                                    <table class='table table-secondary table-hover'>
                                        <thead>
                                            <tr>
                                                <td>User ID</td>
                                                <td>Overall Grade</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                //set pagination
                                                $from_start = ($page - 1) * $rekord_per_page;

                                                //query to check total number of records
                                                if (empty($studentID) && empty($markSearch)) { //both fields empty
                                                    $tsql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000'";
                                                } else if (!empty($studentID) && !empty($markSearch)) { //none fields empty
                                                    $tsql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000' and (User_ID LIKE '%" . $studentID . "%' OR Overall_Grade " . $_SESSION['operationValue'] . " " . $markSearch . ")";
                                                } else if (empty($studentID) && !empty($markSearch)) { //student id field empty
                                                    $tsql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000' and  Overall_Grade " . $_SESSION['operationValue'] . " " . $markSearch . "";
                                                } else if (!empty($studentID) && empty($markSearch)) { //overall grade field empty
                                                    $tsql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000' and User_ID LIKE '%" . $studentID . "%'";
                                                }

                                                //run query and save result
                                                $t_result = $connection->query($tsql);

                                                //count total number of record in result
                                                $total_records = mysqli_num_rows($t_result);

                                                //set how many pagination will be visible totalrecord/record per page
                                                $total_pages = ceil($total_records / $rekord_per_page);

                                                if (empty($studentID) && empty($markSearch)) { //both fields empty
                                                    $sql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000' " . $_SESSION['sortingValue'] . " LIMIT $from_start, $rekord_per_page ";
                                                } else if (!empty($studentID) && !empty($markSearch)) { //none fields empty
                                                    $sql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000' and (User_ID LIKE '%" . $studentID . "%' OR Overall_Grade " . $_SESSION['operationValue'] . " " . $markSearch . ") " . $_SESSION['sortingValue'] . " LIMIT $from_start, $rekord_per_page ";
                                                } else if (empty($studentID) && !empty($markSearch)) { //student id field empty
                                                    $sql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000' and  Overall_Grade " . $_SESSION['operationValue'] . " " . $markSearch . " " . $_SESSION['sortingValue'] . " LIMIT  $from_start, $rekord_per_page ";
                                                } else if (!empty($studentID) && empty($markSearch)) { //overall grade field empty
                                                    $sql = "SELECT * FROM mdb_ni8294f.User_Table WHERE User_ID <> '000000000' and User_ID LIKE '%" . $studentID . "%' " . $_SESSION['sortingValue'] . "  LIMIT $from_start, $rekord_per_page ";
                                                }

                                                $table_data =  mysqli_query($connection, $sql);

                                                $count = mysqli_num_rows($table_data);

                                                if ($count == 0) {
                                                    echo '<span class="label label-danger">No Search results!!</span>';
                                                } else {
                                                    while ($row = mysqli_fetch_array($table_data)) {
                                                        echo "<tr>
                                                               <td><a href='https://stuweb.cms.gre.ac.uk/~ni8294f/projekti_per_wab_mos_kalo/GetStudent.php?User_ID=" . $row["User_ID"] . "'>" . $row["User_ID"] . "</td>";
                                                        echo "<td>";
                                                        if ($row['Overall_Grade'] == null) {
                                                            echo "Not Calculated Yet!";
                                                        }
                                                        echo $row['Overall_Grade'];
                                                        echo "</td> </tr>";
                                                    }
                                                }
                                                ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                            <!--Pagination row -->
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item">
                                                <?php
                                                    echo "<a class='page-link' href='SearchStudent.php' aria-label='Previous'>
                                        <span aria-hidden='true'>&laquo;</span>
                                        <span class='sr-only'>Previous</span>
                                    </a>"; ?>
                                            </li>
                                            <li class='page-item'>
                                                <?php
                                                    for ($i = 1; $i <= $total_pages; $i++) {
                                                        echo "<a class='page-link' href='SearchStudent.php?page=" . $i . "'>" . $i . "</a>";
                                                    }
                                                    ?>
                                            </li>
                                            <li class="page-item">
                                                <?php
                                                    echo "<a class='page-link' href='SearchStudent.php?page=$total_pages' aria-label='Next'> <span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span></a>";
                                                    ?>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-sm-3">

                                </div>
                            </div>
                        </div>
                    <?php } ?>
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