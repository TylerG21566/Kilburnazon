<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <?php include "components/navbar.php" ?>

    <h1>Employee Holiday Reports</h1>


    <!-- Absenteeism Report -->
    <h2 class="mb-4">Absenteeism Report</h2>
    <form id="reportForm">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="startMonth" class="form-label">Start Month</label>
                <select class="form-select" required id="startMonth">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="startYear" class="form-label">Start Year</label>
                <input type="number" required class="form-control" id="startYear" min="1980" max="2050"></input>
            </div>

            <div class="col-md-6 mb-3">
                <label for="endMonth" class="form-label">End Month</label>
                <select class="form-select" required id="endMonth">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="endYear" class="form-label">End Year</label>
                <input type="number" required class="form-control" id="endYear" min="1980" max="2050"></input>
            </div>

        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="specificEmployeeCheck" unchecked>
            <label class="form-check-label" for="specificEmployeeCheck">Specific Employee</label>
        </div>
        <div class="mb-3">
            <label for="employeeIdReport" class="form-label">Employee ID</label>
            <input type="number" class="form-control" id="employeeIdReport" placeholder="No Specific Employee" disabled>
        </div>
        <div class="mb-3">
            <label for="departmentDropdown" class="form-label">Department</label>
            <select class="form-select" id="departmentDropdown" enabled>
                <option value="Marketing">Marketing</option>
                <option value="Technology">Technology</option>
                <option value="Operations">Operations</option>
                <option value="Finance">Finance</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success mb-4" id="generateReport">Generate Report</button>
    </form>

    <div id="reportResultsContainer" class="mt-4">
        <h4>Report Results:</h4>
        <div id="reportResults"></div>
    </div>
    <script>

        function boilerplate(e) {
            e.preventDefault();
            const reportTableBody = document.getElementById('reportResults');
            reportTableBody.innerHTML = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Leave Type</th>
                            <th>Total Days Absent</th>
                            <th>Department</th>
                            <th>Average Absence Rate</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        <!-- Data will be dynamically inserted -->
                        <tr>
                            <td>101</td>
                            <td>Vacation</td>
                            <td>5</td>
                            <td>Sales</td>
                            <td>2%</td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>Sick Leave</td>
                            <td>3</td>
                            <td>Engineering</td>
                            <td>1.5%</td>
                        </tr>
                    </tbody>
                </table>`;
        }

        function generateReport() {

            if (document.getElementById('specificEmployeeCheck').checked) {
                reportType = "byEmployee";
            } else {
                reportType = "byDepartment";
            }
            console.log("reportType", reportType);

            startYear = document.getElementById('startYear').value;
            startMonth = document.getElementById('startMonth').value;

            endYear = document.getElementById('endYear').value;
            endMonth = document.getElementById('endMonth').value;

            employeeId = document.getElementById('employeeIdReport').value;
            console.log("employeeId", employeeId);
            department = document.getElementById('departmentDropdown').value;
            console.log("department", department);

            console.log("startYear", startYear);
            console.log("startMonth", startMonth);
            console.log("endYear", endYear);
            console.log("endMonth", endMonth);
        }

        async function checkEmployeeExists() {
            return new Promise((resolve, reject) => {
                formData = new FormData();
                const employeeId = document.getElementById('employeeIdReport').value;
                formData.append('employee_id', employeeId);
                formData.append('func', 'checkEmployeeExists');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log(response);
                    if (response === 'true') {
                        console.log("true, employee exists");
                        resolve(true);
                    } else {
                        console.log("false, employee exists");
                        resolve(false);
                    }
                };
                xhr.onerror = function () {
                    console.log('Error checking employee existence');
                    resolve(false);
                };
                xhr.send(formData);
            });
        }

        async function validateForm(checkEmployee) {
            // Add your validation logic here
            // if start year is greater than end year
            if (document.getElementById('startYear').value < document.getElementById('endYear').value) {
                // do nothing
            }
            // check if start year is greater than end year
            else if (document.getElementById('startYear').value > document.getElementById('endYear').value) {
                alert('Start year must be less than end year');
                return false;
            } // if not check if start month is greater than end month and start year is equal to end year
            else if (!(document.getElementById('startYear').value == document.getElementById('endYear').value
                && document.getElementById('startMonth').value > document.getElementById('endMonth').value)) {
                alert('Start month must be less than end month');
                return false;
            }

            // check if employee exists
            if (checkEmployee) {
                employeeId = document.getElementById('employeeIdReport').value;
                const employeeExists = await checkEmployeeExists();
                if (!employeeExists) {
                    alert('Employee does not exist');
                    return false;
                }
            }
            console.log("form is valid");
            return true;
        }

        async function generateDepartmentReportTable(data) {

            const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                // Define the table structure
                const tableHeader = `
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Leave Type</th>
                    <th>Total Holidays</th>
                    <th>Total Days</th>
                    <th>Absence Percentage</th>
                </tr>
            </thead>
            <tbody>
    `;

                // Loop through the data and generate table rows
                let tableRows = '';
                data.forEach(row => {
                    const monthName = monthNames[row.holiday_month - 1]; // Get the month name from the array
                    tableRows += `
            <tr>
                <td>${row.holiday_year}</td>
                <td>${monthName}</td>
                <td>${row.leave_type}</td>
                <td>${row.total_holidays}</td>
                <td>${row.total_days}</td>
                <td>${row.average_absence_rate}%</td>
            </tr>
        `;
                });

                // Close the table structure
                const tableFooter = `
            </tbody>
        </table>
    `;

                // Combine header, rows, and footer
                const tableHTML = tableHeader + tableRows + tableFooter;

                // Add the table to the innerHTML of the target element
                document.getElementById('reportResults').innerHTML = tableHTML;
        }

        async function generateDepartmentReport() {
            // get department
            department = document.getElementById('departmentDropdown').value;
            // get start year
            startYear = document.getElementById('startYear').value;
            // get start month
            startMonth = document.getElementById('startMonth').value;
            // get end year
            endYear = document.getElementById('endYear').value;
            // get end month
            endMonth = document.getElementById('endMonth').value;

            formData = new FormData();
            formData.append('department', department);
            formData.append('startYear', startYear);
            formData.append('startMonth', startMonth);
            formData.append('endYear', endYear);
            formData.append('endMonth', endMonth);
            formData.append('func', 'generateDepartmentReport');
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'functions3.php', true);
            xhr.onload = function () {
                const response = xhr.responseText;
                console.log(response);
                // document.getElementById('reportResults').innerHTML = response;
                const data = JSON.parse(response);
                generateDepartmentReportTable(data);
            };
            xhr.send(formData);
        }

        function generateEmployeeReportTable(data) {
            // Define the table structure
                // Array to map month numbers to month names
                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                // Define the table structure
                const tableHeader = `
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Leave Type</th>
                    <th>Total Holidays</th>
                    <th>Total Days</th>
                    <th>Absence Percentage</th>
                </tr>
            </thead>
            <tbody>
    `;

                // Loop through the data and generate table rows
                let tableRows = '';
                data.forEach(row => {
                    const monthName = monthNames[row.holiday_month - 1]; // Get the month name from the array
                    tableRows += `
            <tr>
                <td>${row.holiday_year}</td>
                <td>${monthName}</td>
                <td>${row.leave_type}</td>
                <td>${row.total_holidays}</td>
                <td>${row.total_days}</td>
                <td>${(row.total_days/28).toFixed(4)*100}%</td>
            </tr>
        `;
                });

                // Close the table structure
                const tableFooter = `
            </tbody>
        </table>
    `;

                // Combine header, rows, and footer
                const tableHTML = tableHeader + tableRows + tableFooter;

                // Add the table to the innerHTML of the target element
                document.getElementById('reportResults').innerHTML = tableHTML;
            

        }


        async function generateEmployeeReport() {

            // get employee id
            employeeId = document.getElementById('employeeIdReport').value;
            // get start year
            startYear = document.getElementById('startYear').value;
            // get start month
            startMonth = document.getElementById('startMonth').value;
            // get end year
            endYear = document.getElementById('endYear').value;
            // get end month
            endMonth = document.getElementById('endMonth').value;

            formData = new FormData();
            formData.append('employeeId', employeeId);
            formData.append('startYear', startYear);
            formData.append('startMonth', startMonth);
            formData.append('endYear', endYear);
            formData.append('endMonth', endMonth);
            formData.append('func', 'generateEmployeeReport');
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'functions3.php', true);
            xhr.onload = function () {
                const response = xhr.responseText;
                console.log(response);
                generateEmployeeReportTable(JSON.parse(response)); // Parse JSON string into an array of objects and pass it to the function to generate the tableresponse);
                // document.getElementById('reportResults').innerHTML = response;
            };
            xhr.send(formData);

        }


        // JavaScript to enable/disable the Employee ID input box and clear it when disabled
        const specificEmployeeCheck = document.getElementById('specificEmployeeCheck');
        const employeeIdReport = document.getElementById('employeeIdReport');
        const departmentDropdown = document.getElementById('departmentDropdown');
        employeeIdReport.disabled = true;
        departmentDropdown.disabled = false;
        specificEmployeeCheck.checked = false;



        specificEmployeeCheck.addEventListener('change', function () {
            if (specificEmployeeCheck.checked) {
                // Enable Employee ID input and disable Department dropdown
                employeeIdReport.disabled = false;
                employeeIdReport.placeholder = "Enter Employee ID";
                departmentDropdown.disabled = true;
                departmentDropdown.value = "All Departments"; // Reset dropdown to default value
            } else {
                // Disable Employee ID input and enable Department dropdown
                employeeIdReport.disabled = true;
                employeeIdReport.value = ""; // Clear the input box
                employeeIdReport.placeholder = "No Specific Employee";
                departmentDropdown.disabled = false;
            }
        });
        document.getElementById('reportForm').addEventListener('submit', async function (event) {
            event.preventDefault();
            const employeeCheck = document.getElementById('specificEmployeeCheck').checked;
            if (!(await validateForm())) {
                // alert('Form is not valid');

            } else {
                if (employeeCheck) {
                    await generateEmployeeReport();
                } else {
                    await generateDepartmentReport();
                }
            }

        });
    </script>

</body>

</html>