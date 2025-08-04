<?php

$name = $_POST['name'];
$email = $_POST['email'];
$contract = $_POST['contract'];
$birthDate = $_POST['birth-date'];
$address = $_POST['address'];
$jobTitle = $_POST['job-title'];
$location = $_POST['location'];
$hiredDate = $_POST['hired-date'];
//echo 'hired date: '.$hiredDate;
$salary = $_POST['salary'];
$nationalInsuranceNumber = $_POST['national_insurance_number'];
$imageFile = $_FILES['image'];
$manager = $_POST['manager'];
$emergencyContactName = $_POST['emergency_contact_name'];
$emergencyContactRelationship = $_POST['emergency_contact_relationship'];
$emergencyContactPhone = $_POST['emergency_contact_phone'];

include "db_details.php";
include "Task1_queries.php";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//check if manager exists
if ($manager != -1) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Employees WHERE Employee_ID = ?");
    $stmt->bind_param("i", $manager);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0]. 'here';
    if ($count == 0) { // if manager does not exist return 0 and end
        echo "<h2>Manager id of $manager does not exist, please enter a valid manager ID OR no manager at all </h2>";
        $stmt->close();
        $conn->close();
        exit();
    } 
    $stmt->close();
}

$JuniorDev = '1541';
$FullDev = '1531';
$BackDev = '1521';
$FrontDev = '1511';
$CyberDev = '1551';
$CTO = '1122';
$CEO = '1112';
$PA = '1121';
$COO = '1122';
$CFO = '1122';
$CMO = '1122';
$Brand = '1411';
$Industry = '1421';
$Product = '1431';
$Accountant = '1311';
$Financial = '1321';
$Health = '1211';
$Factory = '1221';
$Delivery = '1231';

switch ($jobTitle) {
    case 'Junior Developer':
        $positionId = $JuniorDev;
        break;
    case 'Full Stack Developer':
        $positionId = $FullDev;
        break;
    case 'Front End Developer':
        $positionId = $FrontDev;
        break;
    case 'Back End Developer':
        $positionId = $BackDev;
        break;
    case 'CTO':
        $positionId = $CTO;
        break;
    case 'CEO':
        $positionId = $CEO;
        break;
    case 'PA':
        $positionId = $PA;
        break;
    case 'COO':
        $positionId = $COO;
        break;
    case 'CFO':
        $positionId = $CFO;
        break;
    case 'CMO':
        $positionId = $CMO;
        break;
    case 'Brand Developer':
        $positionId = $Brand;
        break;
    case 'Industry Researcher':
        $positionId = $Industry;
        break;
    case 'Product Designer':
        $positionId = $Product;
        break;
    case 'Accountant':
        $positionId = $Accountant;
        break;
    case 'Financial Analyst':
        $positionId = $Financial;
        break;
    case 'Health & Safety Officer':
        $positionId = $Health;
        break;
    case 'Factory Worker':
        $positionId = $Factory;
        break;
    case 'Delivery Driver':
        $positionId = $Delivery;
        break;
    
    default:
        $positionId = '1000';
        break;
}



// Fetch existing IDs for the position
$sql = "SELECT Employee_ID FROM Employees WHERE Employee_ID LIKE CONCAT(?, '%')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $positionId);
$stmt->execute();
$result = $stmt->get_result();

$existingIds = [];
while ($row = $result->fetch_assoc()) {
    //echo $row['Employee_ID'];
    $existingIds[] = (int)substr($row['Employee_ID'], 4); // Get the numeric suffix
}
$stmt -> close();
// Generate a unique suffix for the new ID
$newSuffix = 1000; 

// Increment the suffix until it's unique
while (in_array($newSuffix, $existingIds)) {
    $newSuffix++;
}
// Combine the position ID and the unique suffix
$newEmployeeId = $positionId . (string)$newSuffix; // No padding required

// echo $newEmployeeId;
//echo (string)$existingIds;

// Validate and process other fields...
// Handle the uploaded image
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageData = file_get_contents($_FILES['image']['tmp_name']);
    $imageType = $_FILES['image']['type'];

    $stmt = $conn->prepare("INSERT INTO Employees
            (
            Employee_ID,
            name,
            Position,
            salary,
            email,
            date_of_birth,
            Location_ID,
            home_address,
            hired_date,
            contract,
            national_insurance_number,
            image,
            emergency_name,
            emergency_phone,
            emergency_relationship
            )
            VALUES ( ?, ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,   ?, ?, ?, ?)");

    $dummy = "**image**"; // Placeholder for the image column

    $stmt->bind_param(
        "sssssssssssbsss",
        $newEmployeeId,
        $name,
        $jobTitle,
        $salary,
        $email,
        $birthDate,
        $location,
        $address,
        $hiredDate,
        $contract,
        $nationalInsuranceNumber,
        $dummy,
        $emergencyContactName,
        $emergencyContactPhone,
        $emergencyContactRelationship
    );

    $stmt->send_long_data(11, $imageData);
    try {
        $stmt->execute();
        $new_id = $conn->insert_id;
        echo "$new_id"; // success
    } catch (Exception $e) {
        // echo "<h1>Error executing query:</h1> " . $e->getMessage();
        echo "0";
        exit();
    }
    $stmt->close();

    // check if manager exists in Employees


    if ($manager == -1){
        exit();
    } elseif ($manager != -1 and $manager != $new_id) {
        // manager exists

        $stmt = $conn->prepare("SELECT Employee_ID FROM Employees WHERE Employee_ID = ?");
        $stmt->bind_param("i", $manager);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $stmt->close();
            $manager = $row[0];
            // at least one row is returned
            // add employee and manager to the manager table
            if ($manager != $newEmployeeId) {
                $stmt = $conn->prepare("INSERT INTO Managerial_Relationships (manager_id, employee_id) VALUES (?, ?)");
                $stmt->bind_param("ss", $manager, $newEmployeeId);
                $stmt->execute();
                $stmt->close();
            }
        }else{
            $stmt->close();
        }
    }
    $conn->close();

} else {
    echo "-1";
}
?>