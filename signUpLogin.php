<?php

function signup(){
    include 'db_details.php';

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $inputUsername = (string)isset($_POST["username"]) ? trim($_POST["username"]) : null;
    $inputPassword = (string)isset($_POST["password"]) ? trim($_POST["password"]) : null;
    $inputID = (int)isset($_POST["employeeID"]) ? trim($_POST["employeeID"]) : null;

    if (!$inputUsername || !$inputPassword) {
        echo "fail1";
        return;
    }

    $CTO = '1122';
    $CEO = '1112';
    $PA = '1121';
    $COO = '1122';
    $CFO = '1122';
    $CMO = '1122';

    $inputID = (string)$inputID;
    // get the first 4 characters of the Employee_ID
    $positionId = substr($inputID, 0, 4);
    $exec = false;

    switch ($positionId) {
        case $CTO:
            $exec = true;
            break;
        case $CEO:
            $exec = true;
            break;
        case $PA:
            $exec = true;            
            break;
        case $COO:
            $exec = true;
            break;
        case $CFO:
            $exec = true;
            break;
        case $CMO:
            $exec = true;
            break;
        default:
            break;
    }

    if (!$exec) {
        $priv = "emp";
    } else {
        $priv = "exec";
    }
    
    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM Users WHERE UserName = ? OR  Employee_ID = ?");
    $stmt->bind_param("ss", $inputUsername, $inputID);
    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo "fail2";
        return;
    }
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "fail3"; // Username already exists
    } else {
        // Insert the username and hashed password
        
        $passwordHash = password_hash($inputPassword, PASSWORD_DEFAULT);
        $insertStmt = $conn->prepare("INSERT INTO Users (UserName, password_hash, Employee_ID, user_type) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("ssis", $inputUsername, $passwordHash, $inputID, $priv);

        if ($insertStmt->execute()) {
            echo "success";
        } else {
            echo "fail4";
        }
        $insertStmt->close();
    }
    $stmt->close();
    $conn->close();
}

function login(){
    // Enable error reporting for debugging (remove in production)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'db_details.php';

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Corrected assignment of POST data
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : null;
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : null;

    // Check if username and password are provided
    if ($username === null || $password === null) {
        echo "fail";
        $conn->close();
        return;
    }

    // Prepare statement to select user by username
    $stmt = $conn->prepare("SELECT * FROM Users WHERE UserName = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        // Get the result set
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            // Fetch the user data
            $row = $result->fetch_assoc();
            $stored_hash = $row['password_hash'];

            // Verify the password
            if (password_verify($password, $stored_hash)) {
                // Start the session and set session variables
                session_start();
                $_SESSION['username'] = $username;          // Corrected typo
                $_SESSION['E_ID'] = $row['Employee_ID'];
                $_SESSION['Privs'] = $row['user_type'];
                
                echo "success";
                if ($row['user_type'] == 'exec') {
                    echo 'exec';
                } else{
                    echo 'emp';
                }
            } else {
                // Incorrect password
                echo "fail1";
            }
        } else {
            // User not found
            echo "fail2";
        }
    } else {
        // Query execution failed
        echo "fail3";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}


// echo 'yo';
$func = isset($_POST["func"]) ? $_POST["func"] : null;

$func = $_POST["func"];

switch($func){
    case "signup":
        signup();
        break;
    case "login":
        login();
        break;
    default:
        echo "fail";
        break;
}
?>