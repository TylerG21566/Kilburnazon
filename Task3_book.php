<?php

// booking TODO
// DONE: check if employee exists 
// TODO: check if employee has enough holidays
// TODO: update employee holidays & increment total holidays
?>

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

    <h1>Book</h1>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Book Holiday</h2>

                        <p id="remainingHolidays">number of remianing holidays: ...</p>
                    </div>
                    <div class="card-body">
                        <form id="holidayForm">
                            <!-- Start Date -->
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" min="<?php echo date('Y-m-d'); ?>" class="form-control"
                                    id="startDate" name="startDate" required>
                                <div class="invalid-feedback">
                                    Please select a start date.
                                </div>
                            </div>
                            <!-- End Date -->
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input min="<?php echo date('Y-m-d'); ?>" type="date" class="form-control" id="endDate"
                                    name="endDate" required>
                                <div class="invalid-feedback">
                                    Please select an end date.
                                </div>
                            </div>
                            <!-- Leave Type -->
                            <div class="mb-3">
                                <label for="leaveType" class="form-label">Leave Type</label>
                                <select class="form-select" id="leaveType" name="leaveType" required>
                                    <option value="">Select a reason</option>
                                    <option value="vacation">Vacation</option>
                                    <option value="sick_leave">Sick Leave</option>
                                    <option value="personal">Personal</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a leave type.
                                </div>
                            </div>
                            <!-- Comments -->
                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea class="form-control" id="comments" name="comments" rows="3"
                                    placeholder="Additional details..." required></textarea>
                                <div class="invalid-feedback">
                                    Comments must be between 5 and 1000 characters.
                                </div>
                            </div>
                            <!-- Employee ID -->
                            <div class="mb-3">
                                <label for="employeeId" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" id="employeeId" name="employeeId"
                                    placeholder="Enter your Employee ID" required>
                                <div class="invalid-feedback">
                                    Please enter a valid Employee ID.
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Report Section -->
            <div class="card mt-4">
                <div class="card-header text-center bg-secondary text-white">
                    <h4>Report</h4>
                </div>
                <div id = "report_body"class="card-body text-center">
                    <div class="mb-3" id = "reportStartDate">
                        <strong>Start Date:</strong> <span>[Start Date Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportEndDate">
                        <strong>End Date:</strong> <span>[End Date Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportLeaveType">
                        <strong>Leave Type:</strong> <span>[Leave Type Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportComments">
                        <strong>Comments:</strong> <span>[Comments Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportEmployeeId">
                        <strong>Employee ID:</strong> <span>[Employee ID Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "approvedBy">
                        <strong>Employee ID of approver:</strong> <span>[approver Place holder]</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        function calculateDifferences(startDate, endDate) {


            // Difference in years
            let yearsDifference = endDate.getFullYear() - startDate.getFullYear();
            if (
                endDate.getMonth() < startDate.getMonth() ||
                (endDate.getMonth() === startDate.getMonth() && endDate.getDate() < startDate.getDate())
            ) {
                yearsDifference--;
            }

            // Difference in days
            const daysDifference = (endDate - startDate) / (1000 * 60 * 60 * 24);

            return {
                yearsDifference,
                daysDifference
            };
        }



        function checkEmployeeExists() {
            return new Promise((resolve, reject) => {
                formData = new FormData();
                const employeeId = document.getElementById('employeeId').value;
                formData.append('employee_id', employeeId);
                formData.append('func', 'checkEmployeeExists');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log(response);
                    if (response === 'true') {
                        console.log("true, employee exists");
                        document.getElementById('employeeId').classList.remove('is-invalid');
                        document.getElementById('employeeId').classList.add('is-valid');
                        resolve(true);
                    } else {
                        console.log("false, employee exists");
                        document.getElementById('employeeId').classList.remove('is-valid');
                        document.getElementById('employeeId').classList.add('is-invalid');
                        resolve(false);
                    }
                };
                xhr.onerror = function () {
                    reject('Error checking employee existence');
                };
                xhr.send(formData);
            });
        }

        function getEmployeeHiredDate() {
            return new Promise((resolve, reject) => {
                formData = new FormData();
                const employeeId = document.getElementById('employeeId').value;
                formData.append('employee_id', employeeId);
                formData.append('func', 'getCardInfo');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions2_update.php', true);
                xhr.onload = function () {
                    const response = JSON.parse(xhr.responseText);
                    const hiredDate = response['Hired_Date'];
                    resolve(hiredDate);
                };
                xhr.onerror = function () {
                    reject('Error fetching hired date');
                };
                xhr.send(formData);
            });
        }

        function sumOfF(x) { // outputs max number of holidays per year
            return 28 + 2 * x;
        }

        async function getHolidaysTaken(hiredDate) {
            return new Promise((resolve, reject) => {

                const currentDate = new Date();
                const currentDay = currentDate.toISOString().split('T')[0];

                const startDate = document.getElementById('startDate').value;

                formData = new FormData();
                const employeeId = document.getElementById('employeeId').value;
                formData.append('employee_id', employeeId);
                formData.append('func', 'getNumberOfHolidaysTaken');
                formData.append('current_date', startDate);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;

                    const taken = Number(response);
                    console.log('Holidays Taken response', taken);
                    console.log('reseponese', response);
                    resolve(taken);
                };
                xhr.onerror = function () {
                    reject('Error fetching holidays taken');
                };
                xhr.send(formData);
            });
        }

        function calculateRemainingHolidays(hired_date_str, holidays_taken) {
            const currentDate = new Date();
            const hired_date = new Date(hired_date_str);

            console.log('dates', hired_date, currentDate);

            numberOfYears = calculateDifferences(hired_date, currentDate).yearsDifference;
            console.log('Number of years worked', numberOfYears);
            const entitled_holiday_amount = sumOfF(numberOfYears);
            console.log('entitled_holiday_amount', entitled_holiday_amount);
            const remaining = entitled_holiday_amount - holidays_taken;
            return remaining;
        }

        function requestHoliday(formData) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = Number(xhr.responseText);
                    resolve(response);
                };
                xhr.onerror = function () {
                    reject(-1);
                };
                xhr.send(formData);
            })
        }

        function formatReportBody(info) {
            holiday_info = info[0];
            console.log(holiday_info);
            const reportBody = document.getElementById('report_body');
            let approver = holiday_info.approved_by;
            if (approver == null){
                approver = "<strong>not approved yet</strong>";
            }
            reportBody.innerHTML = `
            
            <div id = "report_body"class="card-body text-center">
                    <div class="mb-3" id = "reportStartDate">
                        <strong>Start Date:</strong> <span>${holiday_info.start_date}</span>
                    </div>
                    <div class="mb-3" id = "reportEndDate">
                        <strong>End Date:</strong> <span>${holiday_info.end_date}</span>
                    </div>
                    <div class="mb-3" id = "reportLeaveType">
                        <strong>Leave Type:</strong> <span>${holiday_info.leave_type}</span>
                    </div>
                    <div class="mb-3" id = "reportComments">
                        <strong>Comments:</strong> <span>${holiday_info.comments}</span>
                    </div>
                    <div class="mb-3" id = "reportEmployeeId">
                        <strong>Employee ID:</strong> <span>${holiday_info.Employee_ID}</span>
                    </div>
                    <div class="mb-3" id = "approvedBy">
                        <strong>Employee ID of approver:</strong> <span>${approver}</span>
                    </div>
            </div>
            
            `;
        }

        function getHolidayInfo(holiday_id) {
            // request holiday
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                formData = new FormData();
                formData.append('holiday_id', holiday_id);
                formData.append('func', 'getHolidayInfo');
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log(response);
                    if (response === 'error') {
                        placeholderReportBody();
                        reject('error');
                    }else{
                        const holiday_info = JSON.parse(response);
                        
                        formatReportBody(holiday_info);
                        resolve(holiday_info);
                    }
                    
                };
                xhr.onerror = function () {
                    reject('Error fetching holiday info');
                };
                xhr.send(formData);
            });
        }

        function placeRemainingAnnualLeaveInfo(remaining) {
            document.getElementById('remainingHolidays').innerHTML = 'Number of remaining holidays: ' + remaining;
        }


        async function addHoliday() {
            // get number of holidays taken
            hiredDate = await getEmployeeHiredDate(); // string format
            console.log('hiredDate::', hiredDate);
            const holidays_taken = await getHolidaysTaken(hiredDate); // only gets annual leave
            console.log('holidays_taken', holidays_taken);

            // calculate remaining holidays
            const remaining = calculateRemainingHolidays(hiredDate, holidays_taken);
            console.log('remaining', remaining);

            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const leaveType = document.getElementById('leaveType').value;
            const comments = document.getElementById('comments').value;
            const employeeId = document.getElementById('employeeId').value;

            let holiday_length = calculateDifferences(new Date(startDate), new Date(endDate)).daysDifference;
            console.log('holiday_length', holiday_length);

            if (remaining <= 0 || holiday_length > remaining) {
                alert('Not enough holidays remaining');
                placeRemainingAnnualLeaveInfo(remaining);
                placeholderReportBody();

            } else {
                formData = new FormData();
                formData.append('employee_id', employeeId);
                formData.append('start_date', startDate);
                formData.append('end_date', endDate);
                formData.append('leave_type', leaveType);
                formData.append('holiday_length', holiday_length);
                formData.append('comments', comments);
                formData.append('func', 'requestHoliday');

                const response = await requestHoliday(formData);



                if (response !== -1) {
                    console.log('response', response);
                    alert('Holiday Requested ');
                    placeRemainingAnnualLeaveInfo(remaining - holiday_length);
                    await getHolidayInfo(response);
                    

                } else {
                    alert('failed request');
                    placeRemainingAnnualLeaveInfo(remaining);
                    placeholderReportBody();
                }
            }
        }

        function placeholderReportBody() {
            document.getElementById('report_body').innerHTML = `
            <div class="card-body text-center">
                    <div class="mb-3" id = "reportStartDate">
                        <strong>Start Date:</strong> <span>[Start Date Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportEndDate">
                        <strong>End Date:</strong> <span>[End Date Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportLeaveType">
                        <strong>Leave Type:</strong> <span>[Leave Type Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportComments">
                        <strong>Comments:</strong> <span>[Comments Placeholder]</span>
                    </div>
                    <div class="mb-3" id = "reportEmployeeId">
                        <strong>Employee ID:</strong> <span>[Employee ID Placeholder]</span>
                    </div>
            </div>
            `;
        }

        // checking if form is valid
        document.getElementById('holidayForm').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent form submission
            const form = event.target;
            // Clear previous validation states
            form.classList.remove('was-validated');

            const endDate = document.getElementById('endDate');
            const startDate = document.getElementById('startDate');
            let isValid = true;

            if (new Date(endDate.value) <= new Date(startDate.value)) {
                // End date is before start date
                isValid = false;
                alert('End date must be after the start date.');
                return;
            }
            // Start Date validation

            if (!startDate.value) {
                isValid = false;
                startDate.classList.add('is-invalid');
            } else {
                startDate.classList.remove('is-invalid');
            }
            // End Date validation

            if (!endDate.value) {
                isValid = false;
                endDate.classList.add('is-invalid');
            } else {
                endDate.classList.remove('is-invalid');
            }
            // Leave Type validation
            const leaveType = document.getElementById('leaveType');
            if (!leaveType.value) {
                isValid = false;
                leaveType.classList.add('is-invalid');
            } else {
                leaveType.classList.remove('is-invalid');
            }
            // Comments validation
            const comments = document.getElementById('comments');
            if (comments.value.length < 5 || comments.value.length > 1000) {
                isValid = false;
                comments.classList.add('is-invalid');
            } else {
                comments.classList.remove('is-invalid');
            }
            // Employee ID validation
            const employeeId = document.getElementById('employeeId');
            if (!employeeId.value) {
                isValid = false;
                employeeId.classList.add('is-invalid');
                alert("no employee exists");
            } else if ((await checkEmployeeExists()) === false) {
                employeeId.classList.add('is-invalid');
                isValid = false;
                alert("no employee exists");
            } else {
                employeeId.classList.remove('is-invalid');
                alert("true, employee exists");
            }
            console.log('validity: ', isValid);
            if (isValid) {
                //alert('Form is valid! Submitting...');
                // Form submission logic here
                // Example: form.submit();
                addHoliday();

            } else {
                form.classList.add('was-validated');
            }
        });

        const SUCCESS = 'success';

    </script>

</body>

</html>