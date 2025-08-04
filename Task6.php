<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "components/navbar.php" ?>
<div class="container mt-5">
        <!-- Form -->
        <h2>Termination Audit Form</h2>
        <form id="terminationForm">
            <div class="mb-3">
                <label for="terminatorId" class="form-label">Terminator_ID</label>
                <input type="number" class="form-control" id="terminatorId" placeholder="Enter Terminator_ID">
            </div>
            <div class="mb-3">
                <label for="employeeId" class="form-label">Employee_ID of Deletion</label>
                <input type="number" class="form-control" id="employeeId" placeholder="Enter Employee_ID of Deletion">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr class="my-5">

        <!-- Table -->
        <h2>Termination Report Table</h2>
         <button id="populateButton" class="btn btn-primary">Populate Table</button>
        <h2>Termination Audit Table</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Previous_Employee_ID</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Email</th>
                    <th>Date_of_Birth</th>
                   <!-- <th>Location_ID</th> -->
                    <th>Home_Address</th>
                    <th>Hired_Date</th>
                    <th>Contract</th>
                    <th>National_Insurance_Number</th>
                    <th>Termination_Datetime</th>
                    <th>Scheduled_Deletion_Datetime</th>
                    <th>Termination_ID</th>
                    <th>Terminator_ID</th>
                </tr>
            </thead>
            <tbody id="terminationTableBody">
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <script>

        async function populateTable() {
            xhr = new XMLHttpRequest()
            xhr.open("POST", "functions6.php", true)
            // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
            formData = new FormData()
            formData.append("func", "populateTable")
            xhr.onload = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText)
                    const data = JSON.parse(xhr.responseText)
                    const tableBody = document.getElementById("terminationTableBody")
                    tableBody.innerHTML = ""
                    data.forEach(row => {
                        const newRow = document.createElement("tr")
                        newRow.innerHTML = `
                            <td>${row.Previous_Employee_ID}</td>
                            <td>${row.name}</td>
                            <td>${row.Position}</td>
                            <td>${row.salary}</td>
                            <td>${row.email}</td>
                            <td>${row.date_of_birth}</td>
                            
                            <td>${row.home_address}</td>
                            <td>${row.hired_date}</td>
                            <td>${row.contract}</td>
                            <td>${row.national_insurance_number}</td>
                            <td>${row.termination_datetime}</td>
                            <td>${row.scheduled_deletion_datetime}</td>
                            <td>${row.Termination_id}</td>
                            <td>${row.Terminator_ID}</td>
                        `
                        // <td>${row.Location_ID}</td>
                        tableBody.appendChild(newRow)
                    })
                }
            }
            xhr.send(formData)
        }

        async function Termination(Terminator_ID, Employee_ID) {
            xhr = new XMLHttpRequest()
            xhr.open("POST", "functions6.php", true)
            // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
            formData = new FormData()
            formData.append("Terminator_ID", Terminator_ID)
            formData.append("Employee_ID", Employee_ID)
            formData.append("func", "Termination")
            xhr.onload = function () {
                console.log(xhr.responseText)
                const data = JSON.parse(xhr.responseText)
                alert(data.message)
            }
            xhr.send(formData)
        }
        // Example for handling form submission
        document.getElementById("terminationForm").addEventListener("submit", async function (event) {
            event.preventDefault(); // Prevent page refresh
            const terminatorId = document.getElementById("terminatorId").value;
            const employeeId = document.getElementById("employeeId").value;
            // Logic to handle form submission (e.g., sending data to backend) goes here
            console.log("Form submitted with Terminator_ID:", terminatorId, "Employee_ID:", employeeId);
            await Termination(terminatorId, employeeId);
        });

        document.getElementById("populateButton").addEventListener("click", async function () {
            console.log("click")
            await populateTable();
        });
    </script>
    
</body>
</html>