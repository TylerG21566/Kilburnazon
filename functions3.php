<?php

function requestHoliday()
{

    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = isset($_POST['employee_id']) ? $_POST["employee_id"] : null;

    $start_date = isset($_POST["start_date"]) ? $_POST["start_date"] : null;
    $end_date = isset($_POST["end_date"]) ? $_POST["end_date"] : null;
    $comments = isset($_POST["comments"]) ? $_POST["comments"] : null;
    $leave_type = isset($_POST["leave_type"]) ? $_POST["leave_type"] : null;
    $holiday_length = isset($_POST["holiday_length"]) ? $_POST["holiday_length"] : null;

    $sql = "INSERT INTO Holidays (Employee_ID, start_date, end_date, comments, leave_type, holiday_length)
            VALUES (?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssi", $id, $start_date, $end_date, $comments, $leave_type, $holiday_length);

    if ($stmt->execute()) {
        $last_insert_id = $conn->insert_id;
        echo $last_insert_id;
    } else {
        echo -1;
    }

    $stmt->close();
}

function getNumberOfHolidaysTaken()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = isset($_POST['employee_id']) ? $_POST["employee_id"] : null;
    $current_date = isset($_POST["current_date"]) ? $_POST["current_date"] : null;

    $currentYear = date('Y');

    $sql = 'SELECT COALESCE(SUM(DATEDIFF(end_date, start_date)), 0) AS total_days
            FROM Holidays
            WHERE Employee_ID =' . $id . ' AND YEAR(start_date) = ' . $currentYear . ' AND approved_by IS NOT NULL;';

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo $row['total_days'];
    $conn->close();
}


function checkEmployeeExists()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = isset($_POST['employee_id']) ? $_POST["employee_id"] : null;

    if ($id == null) {
        echo "false";
    } else {
        $sql = 'SELECT * FROM Employees WHERE Employee_ID = ' . $id;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "true";
        } else {
            echo "false";
        }
    }
    $conn->close();
    //echo "nothing";
}
;

function getHolidayInfo()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = isset($_POST['holiday_id']) ? $_POST["holiday_id"] : null;

    $sql = 'SELECT * FROM Holidays WHERE Holiday_ID = ' . $id;
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    $conn->close();
}

function getEmployees()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $manager_id = isset($_POST["manager_id"]) ? $_POST["manager_id"] : null;

    // if manager_id is null return empty array
    if ($manager_id == null) {
        echo json_encode([]);
        $conn->close();
        return;
    }
    //echo "smtg up";
    $sql = "SELECT Employee_ID  FROM managerial_relationships WHERE Manager_ID =" . $manager_id . "";
    $result = $conn->query($sql);
    $employees = array();
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row['Employee_ID'];
    }
    echo json_encode($employees);

    $conn->close();
}

function getHolidayRequestsForEmployee()
{

    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $employee_id = isset($_POST["employee_id"]) ? $_POST["employee_id"] : null;
    //echo $employee_id;

    $sql = "SELECT * FROM Holidays WHERE Employee_ID =" . $employee_id . " AND approved_by IS NULL LIMIT 10";
    $result = $conn->query($sql);
    $holidays = array();
    while ($row = $result->fetch_assoc()) {
        $holidays[] = $row;
    }
    echo json_encode($holidays);
    $conn->close();

}

function declineHolidayRequest()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $holiday_id = isset($_POST["holiday_id"]) ? $_POST["holiday_id"] : null;

    $sql = "DELETE FROM Holidays WHERE Holiday_ID =" . $holiday_id;
    $result = $conn->query($sql);
    echo "true";
    $conn->close();
}

function getEmployeeHiredDate()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $holiday_id = isset($_POST["holiday_id"]) ? $_POST["holiday_id"] : null;

    // echo $holiday_id;

    $sql = "SELECT Employee_ID FROM Holidays WHERE Holiday_ID =" . $holiday_id;
    $result = $conn->query($sql);
    $employee_id = $result->fetch_assoc()['Employee_ID'];
    // echo 'employee id '.$employee_id;

    $sql = "SELECT hired_date FROM Employees WHERE Employee_ID =" . $employee_id;
    $result = $conn->query($sql);
    $hired_date = $result->fetch_assoc()['hired_date'];
    echo $hired_date;
    $conn->close();
}

function getNumberOfHolidaysTakenByHolidayID()
{
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // $id = isset($_POST['employee_id']) ? $_POST["employee_id"] : null;
    // $current_date = isset($_POST["current_date"]) ? $_POST["current_date"] : null;
    $holiday_id = isset($_POST["holiday_id"]) ? $_POST["holiday_id"] : null;

    // get Employee_ID and start_date from Holidays table
    $sql = "SELECT Employee_ID, YEAR(start_date) AS current_year FROM Holidays WHERE Holiday_ID =" . $holiday_id;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $id = $row['Employee_ID'];
    $currentYear = $row['current_year'];

    $sql = 'SELECT COALESCE(SUM(TIMESTAMPDIFF(DAY, start_date, end_date)), 0) AS total_days
        FROM Holidays
        WHERE Employee_ID =' . $id . ' AND YEAR(start_date) = ' . $currentYear . ' AND approved_by IS NOT NULL;';

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo $row['total_days'];
    $conn->close();
}

function getStartDate()
{
    include 'db_details.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    $holiday_id = isset($_POST["holiday_id"]) ? $_POST["holiday_id"] : null;
    $sql = "SELECT start_date FROM Holidays WHERE Holiday_ID =" . $holiday_id;
    $result = $conn->query($sql);
    $start_date = $result->fetch_assoc()['start_date'];
    echo $start_date;
    $conn->close();
}

function getEndDate()
{
    include 'db_details.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    $holiday_id = isset($_POST["holiday_id"]) ? $_POST["holiday_id"] : null;
    $sql = "SELECT end_date FROM Holidays WHERE Holiday_ID =" . $holiday_id;
    $result = $conn->query($sql);
    $end_date = $result->fetch_assoc()['end_date'];
    echo $end_date;
    $conn->close();
}

function approveHoliday()
{
    include 'db_details.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    $holiday_id = isset($_POST["holiday_id"]) ? $_POST["holiday_id"] : null;
    $manager_id = isset($_POST["manager_id"]) ? $_POST["manager_id"] : null;
    $sql = "UPDATE Holidays SET approved_by = " . $manager_id . " WHERE Holiday_ID =" . $holiday_id;
    $result = $conn->query($sql);
    if ($result) {
        echo "true";
    } else {
        echo "false";
        // You can also log the error message for debugging purposes
        // echo "Error: " . $conn->error;
    }
    $conn->close();
}

function debugQuery($query, $params) {
    foreach ($params as $param) {
        // Escape the parameter for SQL (e.g., quotes for strings)
        $value = is_numeric($param) ? $param : "'" . $param . "'";
        // Replace only the first occurrence of "?" in the query
        $query = preg_replace('/\?/', $value, $query, 1);
    }
    return $query;
}

function generateEmployeeReport()
{
    include 'db_details.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    $employee_id = isset($_POST["employeeId"]) ? $_POST["employeeId"] : null;
    $start_month = isset($_POST["startMonth"]) ? $_POST["startMonth"] : null;
    $start_year = isset($_POST["startYear"]) ? $_POST["startYear"] : null;
    $end_month = isset($_POST["endMonth"]) ? $_POST["endMonth"] : null;
    $end_year = isset($_POST["endYear"]) ? $_POST["endYear"] : null;

    // echo "employee id". $employee_id ."start month". $start_month . "start year". $start_year ."end month". $end_month .    "end year". $end_year ."";
    $params = [$employee_id, $start_year, $start_month, $end_year, $end_month];

// Print the query with parameters substituted
// echo "Debug SQL Query:\n";
/*
echo debugQuery("
    SELECT 
        YEAR(start_date) AS holiday_year, 
        MONTH(start_date) AS holiday_month, 
        leave_type, 
        COUNT(*) AS total_holidays,
        SUM(DATEDIFF(end_date, start_date) + 1) AS total_days
    FROM 
        Holidays
    WHERE 
        Employee_ID = ?
        AND approved_by IS NOT NULL
        AND (start_date BETWEEN STR_TO_DATE(CONCAT('?', '-', '?', '-01'), '%Y-%m-%d') 
                           AND LAST_DAY(STR_TO_DATE(CONCAT('?', '-', '?', '-01'), '%Y-%m-%d')))
    GROUP BY 
        YEAR(start_date), MONTH(start_date), leave_type
    ORDER BY 
        holiday_year, holiday_month;", $params);
*/
    $stmt = $conn->prepare("SELECT 
    YEAR(start_date) AS holiday_year, 
    MONTH(start_date) AS holiday_month, 
    leave_type, 
    COUNT(*) AS total_holidays,
    SUM(DATEDIFF(end_date, start_date) + 1) AS total_days
        FROM 
            Holidays
        WHERE 
            Employee_ID = ?
            AND approved_by IS NOT NULL
            AND (start_date BETWEEN STR_TO_DATE(CONCAT( ?, '-', ? , '-01'), '%Y-%m-%d') 
                            AND LAST_DAY(STR_TO_DATE(CONCAT( ? , '-', ? , '-01'), '%Y-%m-%d')))
        GROUP BY 
            YEAR(start_date), MONTH(start_date), leave_type
        ORDER BY 
            holiday_year, holiday_month;");

    $stmt->bind_param("sssss", $employee_id, $start_year, $start_month, $end_year, $end_month);
    $stmt->execute();

    // Fetch the results
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return results as JSON
    header('Content-Type: application/json');
    echo json_encode($data);

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

function generateDepartmentReport(){
    // get all employees in the department
    include 'db_details.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $department = $_POST["department"];
    $start_month = isset($_POST["startMonth"]) ? $_POST["startMonth"] : null;
    $start_year = isset($_POST["startYear"]) ? $_POST["startYear"] : null;
    $end_month = isset($_POST["endMonth"]) ? $_POST["endMonth"] : null;
    $end_year = isset($_POST["endYear"]) ? $_POST["endYear"] : null;



    $stmt = $conn->prepare("SELECT 
    YEAR(start_date) AS holiday_year, 
    MONTH(start_date) AS holiday_month, 
    leave_type, 
    COUNT(*) AS total_holidays,
    SUM(DATEDIFF(end_date, start_date) + 1) AS total_days,
    100 * SUM(DATEDIFF(end_date, start_date) + 1) / 
        (
            (SELECT COUNT(*) 
             FROM Employees 
             INNER JOIN Positions 
             ON Employees.Position = Positions.Position 
             WHERE Department = ?
            ) * DAY(LAST_DAY(STR_TO_DATE(CONCAT(?, '-', ?, '-01'), '%Y-%m-%d')))
        ) AS average_absence_rate
FROM 
    Holidays
WHERE 
    Employee_ID IN (
        SELECT Employee_ID 
        FROM Employees 
        INNER JOIN Positions 
        ON Employees.Position = Positions.Position 
        WHERE Department = ?
    )
    AND approved_by IS NOT NULL
    AND (start_date BETWEEN STR_TO_DATE(CONCAT(?, '-', ?, '-01'), '%Y-%m-%d') 
                    AND LAST_DAY(STR_TO_DATE(CONCAT(?, '-', ?, '-01'), '%Y-%m-%d')))
GROUP BY 
    YEAR(start_date), MONTH(start_date), leave_type
ORDER BY 
    holiday_year, holiday_month;
");

$stmt->bind_param("siisiiii",
    $department,    // Department for the first placeholder
    $start_year,    // Year for DAY(LAST_DAY(...))
    $start_month,   // Month for DAY(LAST_DAY(...))
    $department,    // Department for the WHERE clause
    $start_year,    // Start year for BETWEEN clause
    $start_month,   // Start month for BETWEEN clause
    $end_year,      // End year for BETWEEN clause
    $end_month      // End month for BETWEEN clause
);

    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    $stmt->close();
    $conn->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Request was made using POST method
    $func = $_POST['func'];

    switch ($func) {
        case 'checkEmployeeExists':
            checkEmployeeExists();
            break;
        case 'requestHoliday':
            requestHoliday();
            break;

        case 'getNumberOfHolidaysTakenByHolidayID':
            getNumberOfHolidaysTakenByHolidayID();
            break;

        case 'getNumberOfHolidaysTaken':
            getNumberOfHolidaysTaken();
            break;

        case "getHolidayInfo":
            getHolidayInfo();
            break;

        case "getEmployees":
            getEmployees();
            break;

        case "getHolidayRequestsForEmployee":
            getHolidayRequestsForEmployee();
            break;

        case 'declineHolidayRequest':
            declineHolidayRequest();
            break;

        case 'getEmployeeHiredDate':
            getEmployeeHiredDate();
            break;

        case 'getStartDate':
            getStartDate();
            break;

        case 'getEndDate':
            getEndDate();
            break;

        case 'approveHoliday':
            approveHoliday();
            break;

        case 'generateEmployeeReport':
            generateEmployeeReport();
            break;

        case 'generateDepartmentReport':
            generateDepartmentReport();
            break;


        default:
            break;


    }
}

?>