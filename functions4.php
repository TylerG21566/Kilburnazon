
<?php



function getEmployeePayments(){
    
    include "db_details.php";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->query("SET NAMES 'utf8'");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $employee_id = isset($_POST["employeeID"]) ? $_POST["employeeID"] : null;
    $start_date = isset($_POST["startDate"]) ? $_POST["startDate"] : null;
    $end_date = isset($_POST["endDate"]) ? $_POST["endDate"] : null;

     $stmt = $conn->prepare("SELECT 
    Employee_ID,
    ROUND(SUM(base_pay), 2) AS total_base_pay,
    ROUND(SUM(bonus), 2) AS total_bonus,
    ROUND(SUM(incentives), 2) AS total_incentives,
    ROUND(SUM(tax), 2) AS total_tax,
    ROUND(SUM(allowance), 2) AS total_allowance,
    ROUND(SUM(nic), 2) AS total_nic,
    ROUND(SUM(retirement), 2) AS total_retirement,
    ROUND(SUM(net_employee_pay), 2) AS total_net_employee_pay,
    ROUND(SUM(net_company_pay), 2) AS total_net_company_pay,
    ROUND(SUM(base_pay + bonus + incentives), 2) AS total_gross_pay
    FROM Monthly_Payments
    WHERE Employee_ID = ? AND log_date >= ? AND log_date <= ?");
    $stmt->bind_param("iss", $employee_id, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $arr = array();
    $arr = array_merge($arr, $data);

    $stmt = $conn->prepare("SELECT 
    Employee_ID,
    base_pay AS total_base_pay,
    bonus AS total_bonus,
    incentives AS total_incentives,
    tax AS total_tax,
    allowance AS total_allowance,
    nic AS total_nic,
    retirement AS total_retirement,
    net_employee_pay AS total_net_employee_pay,
    net_company_pay AS total_net_company_pay,
    Round((base_pay + bonus + incentives),2) AS total_gross_pay
FROM Monthly_Payments
WHERE Employee_ID = ? AND log_date >= ? AND log_date <= ?
    
"); // GROUP BY Employee_ID;
    // echo $start_date, " ", $end_date, " ", $employee_id; 
    if (!($stmt->bind_param("iss", $employee_id, $start_date, $end_date))){
        die("ERORORORORO". $stmt->error);
    }
    if (!($stmt->execute())){
        die("DIEEEEEE". $stmt->error);
    }

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $arr = array_merge($arr, $data);
    echo json_encode($arr,  JSON_PRETTY_PRINT);

    $stmt->close();
    $conn->close();
}

function getAllDepartmentSummary(){
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->query("SET NAMES 'utf8'");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $start_date = isset($_POST["startDate"]) ? $_POST["startDate"] : null;
    $end_date = isset($_POST["endDate"]) ? $_POST["endDate"] : null;
    // $department = isset($_POST["department"]) ? $_POST["department"] : null;
    $departments = ["Operations", "Technology", "Finance", "Marketing"];


    $arr = array();


    foreach ($departments as $dep) {
        $stmt = $conn->prepare("SELECT 
        ROUND(SUM(base_pay), 2) AS total_base_pay,
        ROUND(SUM(bonus), 2) AS total_bonus,
        ROUND(SUM(incentives), 2) AS total_incentives,
        ROUND(SUM(tax), 2) AS total_tax,
        ROUND(SUM(allowance), 2) AS total_allowance,
        ROUND(SUM(nic), 2) AS total_nic,
        ROUND(SUM(retirement), 2) AS total_retirement,
        ROUND(SUM(net_employee_pay), 2) AS total_net_employee_pay,
        ROUND(SUM(net_company_pay), 2) AS total_net_company_pay,
        ROUND(SUM(base_pay + bonus + incentives), 2) AS total_gross_pay
        FROM Monthly_Payments
        WHERE log_date >= ? AND log_date <= ? AND
        Monthly_Payments.Employee_ID IN 
        (SELECT Employees.Employee_ID FROM Employees
        INNER JOIN Positions ON Positions.Position = Employees.Position
        WHERE Positions.department = ?)
        ");
        $stmt->bind_param("sss", $start_date, $end_date, $dep);
        $stmt->execute();
        if ($stmt->errno) {
            echo "Error: " . $stmt->error;
            $stmt->close();
        } else {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
                // process the data
                $arrToAdd = array(
                    array(
                        "department"=> $dep,
                        "total_base_pay"=> $data[0]["total_base_pay"],
                        "total_bonus"=> $data[0]["total_bonus"],
                        "total_incentives"=> $data[0]["total_incentives"],
                        "total_tax"=> $data[0]["total_tax"],
                        "total_allowance"=> $data[0]["total_allowance"],
                        "total_nic"=> $data[0]["total_nic"],
                        "total_retirement"=> $data[0]["total_retirement"],
                        "total_net_employee_pay"=> $data[0]["total_net_employee_pay"],
                        "total_net_company_pay"=> $data[0]["total_net_company_pay"],
                        "total_gross_pay"=> $data[0]["total_gross_pay"]
                    )
                );
                $stmt->close();
        
                $arr = array_merge($arr, $arrToAdd);
            } else {
                //echo "No data found";
                $arrToAdd = array(
                    array(
                        "department"=> $dep,
                        "total_base_pay"=> 0,
                        "total_bonus"=> 0,
                        "total_incentives"=> 0,
                        "total_tax"=> 0,
                        "total_allowance"=> 0,
                        "total_nic"=> 0,
                        "total_retirement"=> 0,
                        "total_net_employee_pay"=> 0,
                        "total_net_company_pay"=> 0,
                        "total_gross_pay"=> 0
                    )
                );
                $stmt->close();
        
                $arr = array_merge($arr, $arrToAdd);
            }
        }
        
        
    }
    // $arr = array_merge($arr, $data);        
    echo json_encode($arr ,  JSON_PRETTY_PRINT); // ,  JSON_PRETTY_PRINT

    // $stmt->close();
    $conn->close();
}

function getAllEmployeesInDepartment(){
    include "db_details.php";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->query("SET NAMES 'utf8'");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $start_date = isset($_POST["startDate"]) ? $_POST["startDate"] : null;
    $end_date = isset($_POST["endDate"]) ? $_POST["endDate"] : null;
    $department = isset($_POST["department_of_employees"]) ? $_POST["department_of_employees"] : null;

    $stmt = $conn->prepare("SELECT 
    Monthly_Payments.Employee_ID,
    ROUND(SUM(base_pay), 2) AS total_base_pay,
    ROUND(SUM(bonus), 2) AS total_bonus,
    ROUND(SUM(incentives), 2) AS total_incentives,
    ROUND(SUM(tax), 2) AS total_tax,
    ROUND(SUM(allowance), 2) AS total_allowance,
    ROUND(SUM(nic), 2) AS total_nic,
    ROUND(SUM(retirement), 2) AS total_retirement,
    ROUND(SUM(net_employee_pay), 2) AS total_net_employee_pay,
    ROUND(SUM(net_company_pay), 2) AS total_net_company_pay,
    ROUND(SUM(base_pay + bonus + incentives), 2) AS total_gross_pay
    FROM Monthly_Payments
    WHERE log_date >= ? AND log_date <= ? AND 
    Monthly_Payments.Employee_ID IN 
    (SELECT Employees.Employee_ID FROM Employees
     INNER JOIN Positions ON Positions.Position = Employees.Position
     WHERE Positions.department = ?)
     GROUP BY Monthly_Payments.Employee_ID");
    $stmt->bind_param("sss", $start_date, $end_date, $department);
    $stmt->execute();
    if ($stmt->errno) {
        echo "Error: " . $stmt->error;
        $stmt->close();
    } else {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($data, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(array(), JSON_PRETTY_PRINT);
        }
    }
    $stmt->close();
    $conn->close();
}

$func = $_POST["func"];

switch ($func) {
    case 'getEmployeePayments':
        # code...
        getEmployeePayments();
        break;
    
    case 'getAllDepartmentSummary':
        getAllDepartmentSummary();
        break;

    case 'getAllEmployeesInDepartment': 
        getAllEmployeesInDepartment();
        break;
    
    default:
        # code...
        break;
}

?>
