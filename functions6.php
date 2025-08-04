<?php

function Termination()
{
    // Include database connection details
    include 'db_details.php';

    // Enable error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
    }

    // Get input from the form
    $terminator_id = isset($_POST['Terminator_ID']) ? $_POST['Terminator_ID'] : null;
    $employee_id = isset($_POST['Employee_ID']) ? $_POST['Employee_ID'] : null;

    if ($employee_id === null || $terminator_id === null) {
        die(json_encode(["success" => false, "message" => "Terminator_ID and Employee_ID are required."]));
    }

    // Verify that Terminator_ID exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Employees WHERE Employee_ID = ?");
    $stmt->bind_param("i", $terminator_id);
    $stmt->execute();
    $stmt->bind_result($terminator_exists);
    $stmt->fetch();
    $stmt->close();

    if ($terminator_exists == 0) {
        die(json_encode(["success" => false, "message" => "Terminator_ID does not exist in Employees table."]));
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // 1. Delete the employee
        $deleteQuery = "DELETE FROM Employees WHERE Employee_ID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();

        // Check for errors
        if ($stmt->error) {
            throw new Exception("Deletion failed: " . $stmt->error);
        }

        // Check if the employee was deleted
        if ($stmt->affected_rows === 0) {
            throw new Exception("Employee with Employee_ID $employee_id not found.");
        }

        $stmt->close();

        // 2. Update the TerminationAudit table
        $updateQuery = "
        UPDATE TerminationAudit
        SET Terminator_ID = ?
        WHERE Previous_Employee_ID = ?
        AND termination_datetime = (
            SELECT MAX(termination_datetime)
            FROM TerminationAudit
            WHERE Previous_Employee_ID = ?
        );
        ";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $terminator_id, $employee_id, $employee_id);

        $stmt->execute();

        // Check for errors
        if ($stmt->error) {
            throw new Exception("Update failed: " . $stmt->error);
        }

        // Check if the update affected any rows
        if ($stmt->affected_rows === 0) {
            throw new Exception("Update failed: No matching record found in TerminationAudit.");
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Employee deleted and TerminationAudit updated."]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
}



function getTerminationAudit()
{
    include 'db_details.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM TerminationAudit LIMIT 10";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $func = $_POST["func"];

    switch ($func) {
        case 'Termination':
            Termination();
            # code...
            break;
        case 'populateTable':
            getTerminationAudit();
            # code...
            break;
        default:
            # code...
            break;
    }
}

?>