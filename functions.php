<?php


function searchEmployeeCards()
{
    include "db_details.php";
    include "Task1_queries.php";

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $search = $_POST['search'];
    $department = $_POST['department'];
    $jobTitle = $_POST['jobTitle'];
    $location = $_POST['location'];
    $startDate = $_POST['startDate'];


    $WHERE_stack = [];
    $JOIN_stack = [];
    $SELECT_stack = ["Employees.*"];

    if ($search != "") {
        $WHERE_stack[] = "LOWER(Employees.name) LIKE LOWER('%$search%')";
    } 
    if ($department != "") {
        $WHERE_stack[] = "Positions.department = '$department'";
        
    } 
    $JOIN_stack[] = "INNER JOIN Positions ON Employees.Position = Positions.Position";
    $SELECT_stack[] = "Positions.department";
    
    
    if ($jobTitle != "") {
        $WHERE_stack = "Employees.Position = '$jobTitle'";
    } 
    //$SELECT_stack[] = "Employees.Position AS Employee_Position";
    if ($location) {
        
        $WHERE_stack[] = "Locations.name = '$location'";
        
    } 
    $JOIN_stack[] = "INNER JOIN Locations ON Employees.Location_ID = Locations.Location_ID";
    $SELECT_stack[] = "Locations.name AS Location_Name, Locations.address AS Location_Address, Locations.phone_number AS Location_Phone";
    if ($startDate != "") {
        $WHERE_stack[] = "hired_date >= '$startDate'";
    }

    //builiding sql query
    $sql = "SELECT " . implode(", ", $SELECT_stack) .
        " FROM Employees " . implode(" ", $JOIN_stack);
    if (count($WHERE_stack) > 0) {
        $sql .= " WHERE " . implode(" AND ", $WHERE_stack);
    }
    $sql .= "Limit 10";


    //echo $sql;

    // Execute the query
    // echo "issue";
    $result = $conn->query($sql);


    // Check if the query was successful
    if ($result->num_rows > 0) {


        //$Employee_Phone = $row['phone'];
        while ($row = $result->fetch_assoc()) {
            
            $ID = $row['Employee_ID'];
            $Name = $row['Employee_Name'];
            $Email = $row['email'];
            $Image = $row['image'];
            $Position = $row['Position'];
            $Department = $row['department'];
            $Salary = $row['salary'];
            $Date_of_Birth = $row['date_of_birth'];
            $Hired_Date = $row['hired_date'];
            $Contract = $row['contract'];
            $National_Insurance_Number = $row['national_insurance_number'];
            $Home_Address = $row['home_address'];
            $Location_ID = $row['Location_ID'];
            $Location_Name = $row['Location_Name'];
            $Location_Address = $row['Location_Address'];
            $Location_Phone = $row['Location_Phone'];
            $Emergency_Name = $row['emergency_name'];
            $Emergency_Phone = $row['emergency_phone'];
          

            // Managed By
            $managerList = "";
            $managerSQL = "SELECT Manager_ID FROM Managerial_Relationships 
                            WHERE Employee_ID = $ID";
            $manager = $conn->query($managerSQL);


            if ($manager->num_rows > 0) {
                while ($rowM = $manager->fetch_assoc()) {

                    $managerID = $rowM['Manager_ID'];

                    $managedbySQL = "SELECT Employees.name FROM Employees
                        WHERE Employee_ID = $managerID";

                    $managedby = $conn->query($managedbySQL);
                
                    if ($managedby->num_rows > 0) {
                        while ($rowM2 = $managedby->fetch_assoc()) {
                            $managerName = $rowM2['name'];
                            $managerList .= "<li> $managerName </li>";
                        }
                    }
                }
            }

            echo '
            <div class="card">
                <div class="card-body">
                    <h2 id='. $ID.' ">' . $ID . '</h2>
                    <h3>' . $Name . '</h3>
                    <p>Department: ' . $Department . '</p>
                    <p>Job Title: ' . $Position . '</p>
                    <img alt="no image found" src="data:image;base64,' . base64_encode($Image) . '">
                    <div class="contact-info">
                        <p>Email: <a href="mailto:' . $Email . '">' . $Email . '</a></p>
                    </div>
                    <button class="toggle-additional-info">Show More</button>
                    <a href="./Task2_up.php?id=' . $ID . '"><button class="btn btn-primary">update</button></a>
                    
                    <div class="additional-info">
                        <h4>Managed by:</h4>
                        <ul>'
                . $managerList .
                '</ul>
                        <h4>Emergency Contacts:</h4>
                        <ul>'
                . $emergencyList .
                '</ul>
                        <h4>Salary: ' . $Salary . '</h4>
                        <h4>Date of Birth: ' . $Date_of_Birth . '</h4>
                        <h4>Hired Date: ' . $Hired_Date . '</h4>
                        <h4>Contract: ' . $Contract . '</h4>
                        <h4>National Insurance Number: ' . $National_Insurance_Number . '</h4>
                        <h4>Home Address: ' . $Home_Address . '</h4>
                        <h4>Location ID: ' . $Location_ID . '</h4>
                        <h4>Location Name: ' . $Location_Name . '</h4>
                        <h4>Location Address: ' . $Location_Address . '</h4>
                        <h4>Location Phone: ' . $Location_Phone . '</h4>
                        
                        <ul><h4>Emergency Contacts:</h4>
                        '. $emergencyList .'
                        </ul>
                    </div>
                </div>
            </div>
            ';
        }
    } else {
        echo '
        <h1>No results found</h1>
        ';
    }

    // Close connection
    $conn->close();
}

function searchEmployeeCards2(){
    include 'db_details.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('ERROR: '. $conn->connect_error);
    }

    $nameQuery = isset($_POST['name']) ? $_POST['name'] : null;
    $positionQuery = isset($_POST['position']) ? $_POST['position'] : null;
    $departmentQuery = isset($_POST['department']) ? $_POST['department'] : null;
    $locationQuery = isset($_POST['location']) ? $_POST['location'] : null;
    $hiredQuery = isset($_POST['hired_date']) ? $_POST['hired_date'] : null;
    $Employee_ID = isset($_POST['Employee_ID']) ? $_POST['Employee_ID'] : null;
    $sql = ""; 

    $SELECTS = ['Employees.*'];
    $WHERE = [];
    $JOINS = [];

    if (!($Employee_ID == null || $Employee_ID == "")) {$WHERE[] = "Employees.Employee_ID LIKE '$Employee_ID%'";}

    if (!($nameQuery == null || $nameQuery == "")) {$WHERE[] = "LOWER(Employees.name) LIKE '%$nameQuery%'";}

    if (!($positionQuery == null || $positionQuery == "")) {$WHERE[] = "Employees.Position = '$positionQuery'";}

    if (!($departmentQuery == null || $departmentQuery == "")) {$WHERE[] = "Department = '$departmentQuery'";}

    $JOINS[] = 'INNER JOIN Positions ON Employees.Position = Positions.Position';
    $SELECTS[] = "Positions.department AS Department";

    if (!($locationQuery == null || $locationQuery == '')) {$WHERE[] = "Employees.Location_ID = $locationQuery ";}

    $JOINS[] = "INNER JOIN Locations ON Employees.Location_ID = Locations.Location_ID ";
    $SELECTS[] = "Locations.name AS Location_Name, Locations.address AS Location_Address , Locations.phone_number AS Location_Phone";

    if (!($hiredQuery == null || $hiredQuery == "")) {$WHERE[] = "Employees.hired_date >= '$hiredQuery'";}

    $sql = "SELECT " . implode(", ", $SELECTS) . " FROM Employees " . 
    implode(" ", $JOINS);
    if (count($WHERE) > 0) {
        $sql .= " WHERE ". implode(" AND ", $WHERE);
    }

    $sql.= " ORDER BY Employees.hired_date ASC Limit 10";

    // echo $sql;

    $result = $conn -> query($sql);
    if ($result->num_rows > 0) {
        $response = [];
        //echo $result->num_rows, $sql;
        while ($row = $result->fetch_assoc()) {
            // Managed By
            $managerList = [];
            $managerSQL = "SELECT Manager_ID FROM Managerial_Relationships 
                            WHERE Employee_ID = " . $row["Employee_ID"];
            $manager = $conn->query($managerSQL);


            if ($manager->num_rows > 0) {
                while ($rowM = $manager->fetch_assoc()) {

                    $managerID = $rowM['Manager_ID'];

                    $managedbySQL = "SELECT Employees.name FROM Employees
                        WHERE Employee_ID = $managerID";

                    $managedby = $conn->query($managedbySQL);
                
                    if ($managedby->num_rows > 0) {
                        while ($rowM2 = $managedby->fetch_assoc()) {
                            $managerName = $rowM2['name'];
                            $managerList []= $managerName;
                        }
                    }
                }
            }
            $arr["Location_Name"] = $row["Location_Name"];
            $arr["Location_Address"] = $row["Location_Address"];
            $arr["Location_Phone"] = $row["Location_Phone"];
            $arr["department"] = $row["Department"];
            $arr["Employee_ID"] = $row["Employee_ID"];
            $arr["name"] = $row["name"];    
            $arr["email"] = $row["email"];
            $arr["image"] =  base64_encode($row["image"]);
            $arr["Position"] = $row["Position"];
            $arr["salary"] = $row["salary"];
            $arr["date_of_birth"] = $row["date_of_birth"];
            $arr["hired_date"] = $row["hired_date"];
            $arr["contract"] = $row["contract"];
            $arr["national_insurance_number"] = $row["national_insurance_number"];
            $arr["home_address"] = $row["home_address"];
            $arr["Location_ID"] = $row["Location_ID"];
            $arr["emergency_name"] = $row["emergency_name"];
            $arr["emergency_phone"] = $row["emergency_phone"];
            $arr["emergency_relationship"] = $row["emergency_relationship"];
            $arr["managed_by"] = $managerList[0];
            
            $response[] = $arr;
        }
        //echo "nada";
        header('Content-Type: application/json');
        echo json_encode($response);
    }else{
        echo "fail";
    }
    $conn -> close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $func = $_POST['func'];
    //$employeeID = $_POST['Employee_ID'];

    switch ($func) {
        
        case 'searchEmployeeCards':
            //echo 'yepppp';
            searchEmployeeCards2();
            break;
        default:
            break;
    }

    
}


?>