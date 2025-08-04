<?php
function promoteEmployee()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set values from your form data
    $jobTitle = $_POST['job-title'];
    $percentage = (float) $_POST['promotion_percentage'];
    //echo $jobTitle;
    $salary = $_POST['salary'];
    // echo 'old salary: '.$salary.',';
    $salary = round(($salary * $percentage / 100) + $salary);
    // echo 'new salary: '.$salary.'  ';
    $contract = $_POST['contract'];
    $id = $_POST['id'];



    $stmt = $conn->prepare("UPDATE Employees SET 
    Position = ?, 
    salary = ?, 
    contract = ? 
    WHERE Employee_ID = ?");

    $stmt->bind_param(
        "sisi",
        $jobTitle,
        $salary,
        $contract,
        $id
    );
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
    //echo 'Query: '.$stmt;
    if ($stmt->execute()) {
        echo "success"; // Send a success message to the JavaScript script
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}


function updateEmployee()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set values from your form data
    $name = $_POST['name'];
    $jobTitle = $_POST['job-title'];
    $salary = $_POST['salary'];
    $email = $_POST['email'];
    $birthDate = $_POST['birth-date'];
    $location = $_POST['location'];
    $address = $_POST['address'];
    //$hiredDate = $_POST['hired-date'];
    $contract = $_POST['contract'];
    $nationalInsuranceNumber = $_POST['national_insurance_number'];
    $id = $_POST['id'];
    $manager_id = $_POST['manager'];
    $emergency_name = (string)$_POST['emergency_name'];
    $emergency_number = (string)$_POST['emergency_number'];
    $emergency_relationship = (string)$_POST['emergency_relationship'];
    // echo (string)$emergency_name .'     '. (string)$emergency_number .'  '. (string)$emergency_relationship .'      ';
    $dummy = "";


    $stmt = $conn->prepare("UPDATE Employees SET 
    name = ?, 
    Position = ?, 
    salary = ?, 
    email = ?, 
    date_of_birth = ?, 
    Location_ID = ?, 
    home_address = ?, 
    contract = ?, 
    national_insurance_number = ? ,
    image = ?,
    emergency_name = ?,
    emergency_phone = ?,
    emergency_relationship = ?
    WHERE Employee_ID = ?");

    $stmt->bind_param(
        "sssssisssbsssi",
        $name,
        $jobTitle,
        $salary,
        $email,
        $birthDate,
        $location,
        $address,
        //$hiredDate,
        $contract,
        $nationalInsuranceNumber,
        $dummy,
        $emergency_name,
        $emergency_number,
        $emergency_relationship,
        $id
    );

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];
        $stmt->send_long_data(9, $imageData);
    }

    $stmt->execute();
    $stmt->close();
    // manager exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Employees WHERE Employee_ID = ?");
    $stmt->bind_param("i", $manager_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    $count = $row[0];
    $stmt->close();
    if ($count == 0) {
        echo "Error: " . $stmt->error;
        $conn->close();
        exit();
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM Managerial_Relationships WHERE Employee_ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    $count = $row[0];
    $stmt->close();

    if ($count == 0 and $manager_id == -1) {
        // do nothing
    } elseif ($count == 0 and $manager_id != -1) {// if relation count is 0 and manager given
        $stmt = $conn->prepare("INSERT INTO Managerial_Relationships (manager_id, employee_id) VALUES (?, ?)");
        $stmt->bind_param("ss", $manager_id, $id);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($count > 0 and $manager_id != -1) { // manager given, relation exists
        // UPDATE
        $stmt = $conn->prepare("UPDATE Managerial_Relationships SET Manager_ID = ? WHERE Employee_ID = ?");
        $stmt->bind_param("ss", $manager_id, $id);
        if ($stmt->execute()) {
            echo "success"; // Send a success message to the JavaScript script
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else { // manager not given, relation exists
        // DELETE
        $stmt = $conn->prepare("DELETE FROM Managerial_Relationships WHERE Employee_ID = ?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            echo "success"; // Send a success message to the JavaScript script
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }


    $conn->close();



    /*
    // if manager proposal was given and manager is not the employee
    if ($manager_id!=$id and $manager_id!=-1)
    {
        // check if manager exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Employees WHERE Employee_ID = ?");
        $stmt->bind_param("i", $manager_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $count = $row[0];
        $stmt->close();
        if ($count != 0) { // manager exists so add employee and manager to the manager table

            $stmt = $conn->prepare("UPDATE Managerial_Relationships SET Manager_ID = ? WHERE Employee_ID = ?");
            $stmt->bind_param(
                "ii",
                $manager_id,
                $id
            );
        
            if ($stmt->execute()) {
                echo "success"; // Send a success message to the JavaScript script
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();   
        }
    } elseif($manager_id!=$id)  // if manager proposal was not given
    {
       // echo 'Query: ';
        // remove any previous manager of the employee
        $stmt = $conn->prepare("DELETE FROM Managerial_Relationships WHERE Employee_ID = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "success"; // Send a success message to the JavaScript script
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
        */

    /* 
    
    elseif ($manager->num_rows > 0 and ($managerID == null or $managerID == "")) {
        // UPDATE Managerial_Relationships SET Manager_ID = $managerID WHERE Employee_ID = $id
        // $managerID = $rowM["Manager_ID"];
        $stmt = $conn->prepare("UPDATE Managerial_Relationships SET Manager_ID = ? WHERE Employee_ID = ?");
        $stmt->bind_param("ss", $managerID, $id);
        $stmt->execute();
        $stmt->close();
    }else{
        // add manager
        $managerID = $rowM['Manager_ID'];
        $stmt = $conn->prepare("INSERT INTO Managerial_Relationships (Manager_ID, Employee_ID) VALUES (?, ?)");
        $stmt->bind_param("ss", $managerID, $id);
        $stmt->execute();
        $stmt->close();
    }

    */
    // $conn->close();
}

function getCardInfo()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = isset($_POST['employee_id']) ? $_POST["employee_id"] : null;

    $sql = 'SELECT Employees.* FROM Employees WHERE Employee_ID =' . $id;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $cardInfo = array(
        'id' => $id,
        'Name' => $row['name'],
        'Email' => $row['email'],
        'Image' => base64_encode($row['image']), // Convert BLOB to base64
        'Position' => $row['Position'],
        'Salary' => $row['salary'],
        'Date_of_Birth' => $row['date_of_birth'],
        'Hired_Date' => $row['hired_date'],
        'Contract' => $row['contract'],
        'National_Insurance_Number' => $row['national_insurance_number'],
        'Home_Address' => $row['home_address'],
        'Holidays_Taken' => $row['holidays_taken'],
        'Emergency_Name' => $row['emergency_name'],
        'Emergency_Phone' => $row['emergency_phone'],
        'Emergency_Relationship' => $row['emergency_relationship'],
    );

    $sql = 'SELECT Locations.* FROM Locations WHERE Location_ID =' . $row['Location_ID'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $cardInfo['Location_ID'] = $row['Location_ID'];
    $cardInfo['Location_Name'] = $row['name'];
    $cardInfo['Location_Phone'] = $row['phone_number'];
    // Managed By
    $managerList = [];
    $managerSQL = "SELECT Manager_ID FROM Managerial_Relationships WHERE Employee_ID = $id";
    $manager = $conn->query($managerSQL);
    // manager exists and managerID is not empty
    if ($manager->num_rows > 0) {

        while ($rowM = $manager->fetch_assoc()) {
            $managerID = $rowM['Manager_ID'];
            $managedbySQL = "SELECT Employees.name FROM Employees WHERE Employee_ID = $managerID";
            $managedby = $conn->query($managedbySQL);

            if ($managedby->num_rows > 0) {
                while ($rowM2 = $managedby->fetch_assoc()) {
                    $managerName = $rowM2['name'];
                    $managerList[] = $managerName;
                    $managerListID[] = $managerID;
                }
            }
        }


    }



    $cardInfo['Manager_List'] = $managerList;
    $cardInfo['Manager_List_ID'] = $managerListID;
    header('Content-Type: application/json');
    echo json_encode($cardInfo);
    $conn->close();

}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Request was made using POST method
    $func = $_POST['func'];

    switch ($func) {
        case 'getCardInfo':
            getCardInfo();
            break;
        case 'updateEmployee':
            //echo "success";
            updateEmployee();
            break;
        case "promoteEmployee":
            promoteEmployee();
            break;
        default:
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Request was made using GET method
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Request was made using PUT method
} elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Request was made using DELETE method
}



?>