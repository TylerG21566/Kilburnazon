<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>


</head>

<body>

    <?php include "components/navbar.php" ?>
    <div class="container mt-5">
        <!-- Form -->
        <h2>Payroll Report</h2>
        <div class="container mt-5">
            <form id="dynamicForm" class="border p-4 rounded shadow-sm">


                <div class="mb-3">
                    <label for="startDate" class="form-label">Start Date:</label>
                    <input required type="date" class="form-control" id="startDate" name="startDate">
                </div>

                <div class="mb-3">
                    <label for="endDate" class="form-label">End Date:</label>
                    <input required type="date" class="form-control" id="endDate" name="endDate">
                </div>

                <!-- Radio buttons -->
                <div class="mb-3">
                    <p class="form-label">Select an option:</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selection" id="specificEmployeeRadio"
                            value="specific_employee">
                        <label class="form-check-label" for="specificEmployeeRadio">Specific Employee</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selection" id="departmentAsWholeRadio"
                            value="department_as_whole">
                        <label class="form-check-label" for="departmentAsWholeRadio">Department as a Whole</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selection" id="employeesInDepartmentRadio"
                            value="employees_in_department">
                        <label class="form-check-label" for="employeesInDepartmentRadio">Employees in Department</label>
                    </div>
                </div>

                <!-- Input fields -->
                <div id="specificEmployeeInput" class="mb-3 d-none">
                    <label for="employeeID" class="form-label">Employee Name:</label>
                    <input type="text" class="form-control" id="employeeID" name="employeeID"
                        placeholder="Enter employee ID"></input>
                </div>

                <div id="departmentDropdown" class="mb-3 d-none">
                    <label for="department" class="form-label">Summary of Departments:</label>
                    <select id="department" readonly selected name="department" class="form-select">
                        <option value="All Departments">All Departments</option>
                    </select>
                </div>

                <div id="employeesInDepartmentDropdown" class="mb-3 d-none">
                    <label for="employeesInDepartment" class="form-label">Employees in Department:</label>
                    <select id="employeesInDepartment" name="employeesInDepartment" class="form-select">
                        <option value="Operations">Operations</option>
                        <option value="Finance">Finance</option>
                        <option value="Technology">Technology</option>
                        <option value="Marketing">Marketing</option>
                    </select>
                </div>

                <!-- Submit button -->
                <button id="submitButton" type="button" class="btn btn-primary">Submit</button>
            </form>
            <br><br>
            <button id="downloadCSVButton" class="btn btn-success" onclick="downloadCSV()">Download CSV</button>
            <button id="downloadPDFButton" class="btn btn-danger" onclick="downloadPDF()">Download PDF</button>
            <br><br>
            <hr class="my-5">

            <!-- Table -->

            <h2>Table</h2>
            <table id="Payment Table" class="table table-bordered">
                <thead id="table head" class="table-dark">

                </thead>
                <tbody id="payment table body">
                    <!-- Rows will be dynamically added here -->
                </tbody>
            </table>
        </div>

        <script>

                

            function downloadCSV() {
                    const table = document.querySelector("table"); // Select your HTML table
                    let csvContent = "";
                    table.querySelectorAll("tr").forEach((row, index) => {
                        let rowData = [];
                        row.querySelectorAll("td, th").forEach(cell => {
                            if (index === 1) { // First non-header row
                                rowData.push(`${cell.textContent}`); // Add special markers for emphasis
                            } else {
                                rowData.push(cell.textContent);
                            }
                        });
                        csvContent += rowData.join(",") + "\n"; // Join cells with commas and add a newline
                    });

                    // Create a Blob and a download link
                    const blob = new Blob([csvContent], { type: "text/csv" });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = "table.csv";
                    link.click();
                }

            async function downloadPDF() {
                    const { jsPDF } = window.jspdf; // Get jsPDF instance
                    const pdf = new jsPDF();

                    // Use jsPDF-AutoTable to handle the table conversion
                    const table = document.querySelector("table");
                    const specificEmployeeRadio = document.getElementById('specificEmployeeRadio');
                    const departmentAsWholeRadio = document.getElementById('departmentAsWholeRadio');
                    const employeesInDepartmentRadio = document.getElementById('employeesInDepartmentRadio');
                    // Pass the table element to autoTable
                    pdf.autoTable({
                        html: table,
                        theme: "grid", // Optional: "grid", "striped", or "plain"
                        startY: 10, // Where to start the table in the PDF
                        headStyles: { fillColor: [22, 160, 133] }, // Styling for table headers
                        margin: { top: 20 }, // Margin for the table
                        didParseCell: (data) => {
                            // Check if this is the first non-header row
                            if (data.row.index === 0 && !(departmentAsWholeRadio.checked || employeesInDepartmentRadio.checked)) { // Row index 0 = first non-header row
                                data.cell.styles.fontStyle = "bold";
                            }
                        },
                    });

                    pdf.save("table.pdf"); // Save the generated PDF
                }

            function generateAllDepartmentTable(data){
                /* 
                department
​​
                total_allowance
                ​​
                total_base_pay
                ​​
                total_bonus
                ​​
                total_gross_pay
                ​​
                total_incentives
                ​​
                total_net_company_pay
                ​​
                total_net_employee_pay
                ​​
                total_nic
                ​​
                total_retirement
                ​​
                total_tax
                */

                const tableHead = document.getElementById("table head");
                tableHead.innerHTML = `<tr>
                    <th>Department</th>
                    <th>Total Allowance</th>
                    <th>Total Base Pay</th>
                    <th>Total Bonus</th>
                    <th>Total Gross Pay</th>
                    <th>Total Incentives</th>
                    <th>Total Net Company Pay</th>
                    <th>Total Net Employee Pay</th>
                    <th>Total NIC</th>
                    <th>Total Retirement</th>
                    <th>Total Tax</th>
                </tr>`;

                let fatty = false;

                const tableBody = document.getElementById("payment table body");
                data.forEach(obj => {

                    const row = document.createElement("tr");
                    if (fatty) {
                        fatty = false;
                        row.innerHTML = `<th>${obj.department}</th>
                    <th>${obj.total_allowance}</th>
                    <th>${obj.total_base_pay}</th>
                    <th>${obj.total_bonus}</th>
                    <th>${obj.total_gross_pay}</th>
                    <th>${obj.total_incentives}</th>
                    <th>${obj.total_net_company_pay}</th>
                    <th>${obj.total_net_employee_pay}</th>
                    <th>${obj.total_nic}</th>
                    <th>${obj.total_retirement}</th>
                    <th>${obj.total_tax}</th>`;
                    }else{
                        row.innerHTML = `<td>${obj.department}</td>
                    <td>${obj.total_allowance}</td>
                    <td>${obj.total_base_pay}</td>
                    <td>${obj.total_bonus}</td>
                    <td>${obj.total_gross_pay}</td>
                    <td>${obj.total_incentives}</td>
                    <td>${obj.total_net_company_pay}</td>
                    <td>${obj.total_net_employee_pay}</td>
                    <td>${obj.total_nic}</td>
                    <td>${obj.total_retirement}</td>
                    <td>${obj.total_tax}</td>`;
                    }
                    
                    tableBody.appendChild(row);
                    
                });
            }

            function generateEmployeeTable(data) {
                /* 
                Employee_ID
​​
                total_allowance
                ​​
                total_base_pay
                ​​
                total_bonus
                ​​
                total_gross_pay
                ​​
                total_incentives
                ​​
                total_net_company_pay
                ​​
                total_net_employee_pay
                ​​
                total_nic
                ​​
                total_retirement
                ​​
                total_tax
                */
                // generate head
                const specificEmployeeRadio = document.getElementById('specificEmployeeRadio');
                const departmentAsWholeRadio = document.getElementById('departmentAsWholeRadio');
                const employeesInDepartmentRadio = document.getElementById('employeesInDepartmentRadio');
                const tableHead = document.getElementById("table head");
                tableHead.innerHTML = `<tr>
                    <th>Employee ID</th>
                    <th>Total Allowance</th>
                    <th>Total Base Pay</th>
                    <th>Total Bonus</th>
                    <th>Total Gross Pay</th>
                    <th>Total Incentives</th>
                    <th>Total Net Company Pay</th>
                    <th>Total Net Employee Pay</th>
                    <th>Total NIC</th>
                    <th>Total Retirement</th>
                    <th>Total Tax</th>
                </tr>`;

                let fatty = true;

                if (employeesInDepartmentRadio.checked) {
                    fatty = false;
                }
                

                const tableBody = document.getElementById("payment table body");
                data.forEach(obj => {

                    const row = document.createElement("tr");
                    if (fatty) {
                        fatty = false;
                        row.innerHTML = `<th>${obj.Employee_ID}</th>
                    <th>${obj.total_allowance}</th>
                    <th>${obj.total_base_pay}</th>
                    <th>${obj.total_bonus}</th>
                    <th>${obj.total_gross_pay}</th>
                    <th>${obj.total_incentives}</th>
                    <th>${obj.total_net_company_pay}</th>
                    <th>${obj.total_net_employee_pay}</th>
                    <th>${obj.total_nic}</th>
                    <th>${obj.total_retirement}</th>
                    <th>${obj.total_tax}</th>`;
                    }else{
                        row.innerHTML = `<td>${obj.Employee_ID}</td>
                    <td>${obj.total_allowance}</td>
                    <td>${obj.total_base_pay}</td>
                    <td>${obj.total_bonus}</td>
                    <td>${obj.total_gross_pay}</td>
                    <td>${obj.total_incentives}</td>
                    <td>${obj.total_net_company_pay}</td>
                    <td>${obj.total_net_employee_pay}</td>
                    <td>${obj.total_nic}</td>
                    <td>${obj.total_retirement}</td>
                    <td>${obj.total_tax}</td>`;
                    }
                    
                    tableBody.appendChild(row);
                    
                });
            }

            async function getAllEmployeesInDepartment() {
                const form = document.getElementById('dynamicForm');
                const formData = new FormData(form);
                formData.append('department_of_employees', document.getElementById('employeesInDepartment').value);
                formData.append('func', 'getAllEmployeesInDepartment');
                try {
                    const response = await fetch('functions4.php', {
                        method: 'POST',
                        body: formData,
                    }).then(response => response.text()).then(data => {
                        const jsonData = JSON.parse(data); // Parse the response text into a JSON object
                        return jsonData;
                    })
                    console.log(response);
                    return response;
                } catch (error) {
                    return [];
                }
                
            }

            async function getAllDepartmentSummary() {
                const form = document.getElementById('dynamicForm');
                const formData = new FormData(form);
                formData.append('func', 'getAllDepartmentSummary');
                try {
                    const response = await fetch('functions4.php', {
                        method: 'POST',
                        body: formData,
                    }).then(response => response.text()).then(data => {
                        const jsonData = JSON.parse(data); // Parse the response text into a JSON object
                        jsonData.forEach(item => {
                            Object.keys(item).forEach(key => {
                                if (typeof item[key] === 'number' && key !== 'department') {
                                    item[key] = Number(item[key].toFixed(2));
                                }
                            });
                        });

                        return jsonData;
                    })
                    console.log(response);
                    return response;
                } catch (error) {
                    return [];
                }
            }

            async function getEmployeePayments() {
                const form = document.getElementById('dynamicForm');
                const formData = new FormData(form);
                formData.append('func', 'getEmployeePayments');
                try {
                    
                    const response = await fetch('functions4.php', {
                        method: 'POST',
                        body: formData,
                    }).then(response => response.text()).
                    then(data => {
                        
                        const jsonData = JSON.parse(data); // Parse the response text into a JSON object
                        jsonData.forEach(item => {
                            Object.keys(item).forEach(key => {
                                if (typeof item[key] === 'number' && key !== 'employee_id') {
                                    item[key] = Number(item[key].toFixed(2));
                                }
                            });
                        });

                        return jsonData;
                    });
                    console.log(response);
                    return response;
                } catch (error) {
                    console.error('Error:', error);
                }
            }


            async function validateForm() {
                const specificEmployeeChecked = document.getElementById('specificEmployeeRadio').checked;
                const departmentAsWholeChecked = document.getElementById('departmentAsWholeRadio').checked;
                const employeesInDepartmentChecked = document.getElementById('employeesInDepartmentRadio').checked;

                if (specificEmployeeChecked) {
                    console.log("Specific employee selected");
                } else if (departmentAsWholeChecked) {
                    console.log("Department as a whole selected");
                } else if (employeesInDepartmentChecked) {
                    console.log("Employees in department selected");
                } else {
                    return false;
                }

                // get start date
                const startDate = document.getElementById('startDate').value;
                // get end date
                const endDate = document.getElementById('endDate').value;

                // if start date is greater than end date
                if (new Date(startDate) >= new Date(endDate)) {
                    alert('End date must be after the start date.');
                    return false;
                }
                return true;
            }

            async function submitForm() {
                const specificEmployeeChecked = document.getElementById('specificEmployeeRadio').checked;
                const departmentAsWholeChecked = document.getElementById('departmentAsWholeRadio').checked;
                const employeesInDepartmentChecked = document.getElementById('employeesInDepartmentRadio').checked;

                if (specificEmployeeChecked) {
                    console.log("Specific employee selected");
                    const data = await getEmployeePayments();
                    if (data.length > 1) {
                        generateEmployeeTable(data);
                        hideRevealDownloadButtons();
                    }
                    else {
                        const tableBody = document.getElementById("payment table body");
                        tableBody.innerHTML = "";
                        const tablehead = document.getElementById("table head");
                        tablehead.innerHTML = "";
                        alert("No Employee found");
                        hideRevealDownloadButtons();
                    }
                } else if (departmentAsWholeChecked) {
                    console.log("Department as a whole selected");
                    const data = await getAllDepartmentSummary();
                    console.log("DATA", data);
                    if (data.length > 1) {
                        // generateEmployeeTable(data);
                        generateAllDepartmentTable(data);
                        hideRevealDownloadButtons();
                    }
                    else {
                        const tableBody = document.getElementById("payment table body");
                        tableBody.innerHTML = "";
                        const tablehead = document.getElementById("table head");
                        tablehead.innerHTML = "";
                        alert("Departments data found");
                        hideRevealDownloadButtons();
                    }
                } else if (employeesInDepartmentChecked) {
                    const data = await getAllEmployeesInDepartment();
                    console.log("DATA", data);
                    if (data.length > 0) {
                        generateEmployeeTable(data);
                        // generateAllDepartmentTable(data);
                        hideRevealDownloadButtons();
                    }
                    else {
                        const tableBody = document.getElementById("payment table body");
                        tableBody.innerHTML = "";
                        const tablehead = document.getElementById("table head");
                        tablehead.innerHTML = "";
                        alert("No employees in department have been paid yet");
                        hideRevealDownloadButtons();
                    }
                }
                console.log("Form submitted");
            }

            const th = document.getElementById("table head");
            const tbody = document.getElementById("payment table body");

            document.getElementById('submitButton').addEventListener('click', async function () {
                // clear table
                const tableBody = document.getElementById("payment table body");
                tableBody.innerHTML = "";
                const tablehead = document.getElementById("table head");
                tablehead.innerHTML = "";
                if (await validateForm()) {
                    await submitForm();
                }
            })


            /* Front end styling logic */
            /* element variables */
            const specificEmployeeRadio = document.getElementById('specificEmployeeRadio');
            const departmentAsWholeRadio = document.getElementById('departmentAsWholeRadio');
            const employeesInDepartmentRadio = document.getElementById('employeesInDepartmentRadio');

            const specificEmployeeInput = document.getElementById('specificEmployeeInput');
            const departmentDropdown = document.getElementById('departmentDropdown');
            const employeesInDepartmentDropdown = document.getElementById('employeesInDepartmentDropdown');

            const tableBody = document.getElementById('Payment table body');
            const table = document.getElementById('Payment Table');

            /* Functions */
            function hideRevealDownloadButtons() {
                console.log("hideRevealDownloadButtons");
                if (document.getElementById("Payment Table").rows.length === 0) {
                    // hide download buttons
                    document.getElementById("downloadCSVButton").classList.add('d-none');
                    document.getElementById("downloadPDFButton").classList.add('d-none');
                }else{
                    // show download buttons
                    document.getElementById("downloadCSVButton").classList.remove('d-none');
                    document.getElementById("downloadPDFButton").classList.remove('d-none');
                }
            }

            function updateForm() {
                specificEmployeeInput.classList.add('d-none');
                departmentDropdown.classList.add('d-none');
                employeesInDepartmentDropdown.classList.add('d-none');

                if (specificEmployeeRadio.checked) {
                    specificEmployeeInput.classList.remove('d-none');
                } else if (departmentAsWholeRadio.checked) {
                    departmentDropdown.classList.remove('d-none');
                } else if (employeesInDepartmentRadio.checked) {
                    employeesInDepartmentDropdown.classList.remove('d-none');
                }
            }

            /* Event Listeners */
            specificEmployeeRadio.addEventListener('change', updateForm);
            departmentAsWholeRadio.addEventListener('change', updateForm);
            employeesInDepartmentRadio.addEventListener('change', updateForm);
            // table.addEventListener('change', hideRevealDownloadButtons);

            /* Initial State */
            specificEmployeeRadio.checked = true;
            updateForm();
            document.getElementById("downloadCSVButton").classList.add('d-none');
            document.getElementById("downloadPDFButton").classList.add('d-none');


        </script>
</body>

</html>