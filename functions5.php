<?php

function getBirthdays() {
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "CALL `BirthDays`(); ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<ul class="list-group">';
        while ($row = $result->fetch_assoc()) {
            $name = $row['name'];
            $email = $row['email'];
            $dob = $row['date_of_birth'];
            $birthday = $row['birthday_date'];
            $address = $row['home_address'];

            
            $age = date('Y') - date('Y', strtotime($dob));
            if (date('md') < date('md', strtotime($dob))) {
                $age--;
            }

            // Example: Send email (requires configuration of an email library like PHPMailer)
            echo "<li class='list-group-item'> $name ($email) - DOB:$birthday - Age: $age - Address: $address </li>";
        }
        echo "</ul>";
    } else {
        echo "No birthdays this month.";
    }

    $conn->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Request was made using POST method
    $func = $_POST['func'];

    switch ($func) {
        
        case 'getBirthdays':
            getBirthdays();
            break;
        default:
            break;
    }
} 


?>