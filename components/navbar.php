<?php

$name = $_POST['name'];
$department = $_POST['department'];
$jobTitle = $_POST['job-title'];
$location = $_POST['location'];
$hiredDate = $_POST['hired-date'];


session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: index.php');
    session_destroy();
    exit();
}else{
    $username = $_SESSION['username'];
    $employeeID = $_SESSION['E_ID'];
    $privileges = $_SESSION['Privs'];
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Employee Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php">Login/Sign up</a>
                    </li>
                    
                
                    
<!-- update is only accessible from search -->
                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="./Task2_up.php">Update & Promote Employee</a>
                    </li>
                    -->
                    <?php if ($privileges == 'exec') { ?>
                        <!-- display executive content -->

                        <li class="nav-item">
                        <a class="nav-link" href="./Task1.php">Search for Employees</a>
                    </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="./Task3_book.php">Book Holiday</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./Task3_approve.php">Approve Holiday</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./Task3_holiday_reports.php">Holiday Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Task4.php">Payroll Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Task5.php">BirthDays!!!</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Task6.php">Termination Audit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Task1.php">Search for Employees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Task2.php">Add Employee</a>
                    </li>
                
                    <?php } else { ?>
                        <!-- display alternative content or message -->
                      
                        <li class="nav-item">
                        <a class="nav-link" href="./Task3_book.php">Book Holiday</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./Task3_approve.php">Approve Holiday</a>
                        
                    <?php } ?>
                    


                </ul>
            </div>
        </div>
    </nav>