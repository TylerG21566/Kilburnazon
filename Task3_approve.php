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
    <h1>approve</h1>
    <p class="lead">enter employee id of the a manager to see requests and approve leave/holiday requests</p>
    <h3 id="status" class="lead">Status:</h3>

    <form action="" method="post" id="manager-form">
        <label for="manager_id">Employee id of Manager:</label>
        <input type="number" min="0" name="manager_id" id="manager_id">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <div class="container py-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="requests">
            <!--
    <div class="col">
  <div class="card">
-->
        </div>
    </div>

    <script>

        function calculateDifferences(startDate, endDate) {
            // Difference in years
            let yearsDifference = endDate.getFullYear() - startDate.getFullYear();
            if (endDate.getMonth() < startDate.getMonth() ||
                (endDate.getMonth() === startDate.getMonth() && endDate.getDate() < startDate.getDate())
            ) 
            yearsDifference = yearsDifference - 1;

            // Difference in days
            const daysDifference = (endDate - startDate) / (1000 * 60 * 60 * 24);
            return {
                yearsDifference,
                daysDifference
            };
        }

        async function checkManagerExists() {
            return new Promise((resolve, reject) => {
                formData = new FormData();
                const employeeId = document.getElementById('manager_id').value;
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
                    reject('Error checking employee existence');
                };
                xhr.send(formData);
            });
        }

        async function getEmployees(id) {

            console.log("getting employees id:", id);

            return new Promise((resolve, reject) => {
                formData = new FormData();
                formData.append('manager_id', id);
                formData.append('func', 'getEmployees');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    if (xhr.responseText == "empty") 
                    {
                        document.getElementById('status').textContent = "no requests";
                        resolve([]);
                    }else{
                        const response = JSON.parse(xhr.responseText);
                        console.log('getEmployees Response', response);
                        resolve(response);
                    }
                    
                };
                xhr.onerror = function () {
                    reject('Error checking employee existence');
                };
                xhr.send(formData);
            });

        }


        function buildRequestCard(data) {
            console.log(data);
            requests = document.getElementById('requests');
            requests.innerHTML += `
            <div class="col" id="card_${data.Holiday_ID}">
                <div class="card">
                    <div class="card-header text-center bg-secondary text-white">
                    <h4>Report for employee: ${data.Employee_ID} - Holiday_ID: ${data.Holiday_ID}</h4>
                    </div>
                    <div id="report_body" class="card-body text-center">
                        <div class="mb-3" id="reportStartDate">
                            <strong>Start Date:</strong> <span id="start_date-${data.Holiday_ID}">${data.start_date}</span>
                        </div>
                        <div class="mb-3" id="reportEndDate">
                            <strong>End Date:</strong> <span id="end_date-${data.Holiday_ID}">${data.end_date}</span>
                        </div>
                        <div class="mb-3" id="reportLeaveType">
                            <strong>Leave Type:</strong> <span id="leave_type-${data.Holiday_ID}">${data.leave_type}</span>
                        </div>
                        <div class="mb-3" id="reportComments">
                            <strong>Comments:</strong> <span id="comments-${data.Holiday_ID}">${data.comments}</span>
                        </div>
                        <div class="mb-3" id="reportEmployeeId">
                            <strong>Employee ID:</strong> <span id="Employee_ID-${data.Holiday_ID}">${data.Employee_ID}</span>
                        </div>
                    
                        <button type='button' id="approve-btn-${data.Holiday_ID}" class="btn btn-primary">Approve</button>
                        <button type='button' id="decline-btn-${data.Holiday_ID}" class="btn btn-danger">Decline</button>
                    
                    </div>
                </div>
                </div>
            `;
            console.log("bindddddd");

            /*
                        document.getElementById(`approve-form-${data.Holiday_ID}`).addEventListener('submit', 
                        function (event) {
                            console.log('Form submitted!');
                            event.preventDefault(); // Prevent form from actually submitting
                            
                            // Get the button that was clicked
                            const buttonClicked = document.activeElement.id;
                            console.log('Button clicked:', buttonClicked);
                            
                            // You can then use the buttonClicked value to determine which action to take
                            if (buttonClicked === 'approve-btn') {
                                console.log('Approve action',data.Holiday_ID);
                                // Add your approve logic here
                            } else if (buttonClicked === 'decline-btn') {
                                console.log('Decline action',data.Holiday_ID);
                                // Add your decline logic here
                            }
                            return false
                            }
                        );
                        */
        }

        async function generateHolidayRequestCards(employeeArray) {

            requests = document.getElementById('requests');
            requests.innerHTML = '';
            for (let i = 0; i < employeeArray.length; i++) {
                buildRequestCard(employeeArray[i]);
            }

        }

        async function getHolidayRequestsForEmployee(employeeId) {

            return new Promise((resolve, reject) => {
                formData = new FormData();
                console.log("getting holiday requests for employee id:", employeeId);
                formData.append('employee_id', employeeId);
                formData.append('func', 'getHolidayRequestsForEmployee');
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const text = xhr.responseText;
                    console.log(text);
                    const response = JSON.parse(text);

                    resolve(response);
                };
                xhr.onerror = function () {
                    reject('Error checking employee existence');
                };
                xhr.send(formData);
            });
        }


        async function getHolidayRequests() {

            id = document.getElementById('manager_id').value;
            const managerExists = await checkManagerExists();

            // check if manager exists    --------------------
            if (!(managerExists)) {
                document.getElementById('status').textContent = `no manager ${id} exists!`;
                return "manager does not exist";
            }
            console.log("manager exists");

            // check if manager manages any employees.
            // if so you should get a array of employees managed by manager
            formData = new FormData(document.getElementById('manager-form'));
            const employees = await getEmployees(id); //

            console.log(
                "--------------------------------------"
            );

            if (employees.length === 0) {
                document.getElementById('status').textContent = "manager manages no employees OR no Requests to be approved";
                return "manager manages no employees";
            }

            // get holiday requests for each employee
            const employeesData = [];
            for (let i = 0; i < employees.length; i++) {
                const employeeData = await getHolidayRequestsForEmployee(employees[i]);
                employeesData.push(...employeeData);

            }
            console.log(employeesData);

            // build holiday request cards
            generateHolidayRequestCards(employeesData);
            document.getElementById('status').textContent = "success";
            return "success";
        }

        /*
            TODO: check if enough remaining holidays
            TDOO: if not jut put a message into status
            TDOO: add approved by 
        */

        checkForEnoughHolidays = () => {
            const start_date_str = document.getElementById(`start_date-${Holiday_ID}`);
            const end_date_str = document.getElementById(`end_date-${Holiday_ID}`);

            const start_date = new Date(start_date_str);
            const end_date = new Date(end_date_str);

            const differences = calculateDifferences(start_date, end_date).daysDifference;
            const remaining = calculateRemainingHolidays(start_date_str, holidays_taken);
        }
        /* BGEIN same as Task3 book */

        function sumOfF(x) { // outputs max number of holidays per year
            return 28 + 2 * x;
        }

        async function getHolidaysTaken(Holiday_ID) {
            return new Promise((resolve, reject) => {

                // const currentDate = new Date();
                // const currentDay = currentDate.toISOString().split('T')[0];

                //const startDate = document.getElementById(`start_date-${Holiday_ID}`).value;
                // console.log("holiday id", Holiday_ID,'startDate', startDate);

                formData = new FormData();
                const employeeId = document.getElementById(`Employee_ID-${Holiday_ID}`).value;
                formData.append('holiday_id', Holiday_ID);
                formData.append('func', 'getNumberOfHolidaysTakenByHolidayID');
                // formData.append('current_date', startDate);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log('reseponese', response);

                    const taken = Number(response);
                    console.log('Holidays Taken response', taken);
                    
                    resolve(taken);
                };
                xhr.onerror = function () {
                    reject('Error fetching holidays taken');
                };
                console.log("here?")
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

        /* END */
        // FINISH 1
        async function getEmployeeHiredDate(holiday_id) {

            return new Promise((resolve, reject) => {
                //const employeeId = document.getElementById(`Employee_ID-${holiday_id}`).value;
                formData = new FormData();
                formData.append('holiday_id', holiday_id);
                formData.append('func', 'getEmployeeHiredDate');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log(response);
                    const employeeHiredDate = response;
                    resolve(employeeHiredDate);
                };
                xhr.onerror = function () {
                    reject('Error checking employee existence');
                };
                console.log('oh no')
                xhr.send(formData);
            });
        }

        async function getStartDate(id) {
            return new Promise((resolve, reject) => {
                formData = new FormData();
                formData.append('holiday_id', id);
                formData.append('func', 'getStartDate');
                xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log(response);
                    const employeeHiredDate = response;
                    resolve(employeeHiredDate);
                };
                xhr.onerror = function () {
                    reject('Error checking employee existence');
                };
                console.log('oh no')
                xhr.send(formData);
            });
        }

        async function getEndDate(id) {
            return new Promise((resolve, reject) => {
                formData = new FormData();
                formData.append('holiday_id', id);
                formData.append('func', 'getEndDate');
                xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log(response);
                    const employeeHiredDate = response;
                    resolve(employeeHiredDate);
                };
                xhr.onerror = function () {
                    reject('Error checking employee existence');
                };
                console.log('oh no')
                xhr.send(formData);
            });
        }

        async function approveHoliday(holiday_id, manager_id) {
            formData = new FormData();
            formData.append('holiday_id', holiday_id);
            formData.append('manager_id', manager_id);
            formData.append('func', 'approveHoliday');
            xhr = new XMLHttpRequest();
            xhr.open('POST', 'functions3.php', true);
            xhr.onload = function () {
                const response = xhr.responseText;
                console.log(response);
            };
            xhr.onerror = function () {
                reject('Error checking employee existence');
            };
            await xhr.send(formData);
        }

        async function approveHolidayRequest(id) {
            try {
                const manager_id = document.getElementById('manager_id').value;
                const hiredDate = await getEmployeeHiredDate(id);

                // STEP 1: get number of holidays taken in year of start date
                
                // STEP 2: calculate number of remaining holidays
                const holidaysTakenInYear = await getHolidaysTaken(id);
                // STEP 3: get start date and end date
                const startDate =  await getStartDate(id);
                const endDate = await getEndDate(id);
                console.log('start date', startDate);
                console.log('end date', endDate);
                // STEP 4: calculate difference between start date and end date
                const difference = calculateDifferences(new Date(startDate), new Date(endDate)).daysDifference;
                const remaining = calculateRemainingHolidays(hiredDate, holidaysTakenInYear);
                // STEP 5: check if difference is less than remaining holidays

                console.log('remaining', remaining);

                if (remaining >= difference) { // STEP 6: if so, call function to approve holiday request
                    // Call your approval function here if needed
                    await approveHoliday(id, manager_id);
                    const div = document.getElementById('card_' + id);
                    div.remove();
                    console.log('approved');
                    
                } else { // STEP 7: if not, alert user
                    alert('Not enough holidays remaining');
                }
            
                

                return true; // Resolve the function
            } catch (error) {
                console.error('Error in approving holiday request:', error);
                throw error; // Reject the function if there's an error
            }
        }


        /*async function approveHolidayRequest(id){

            const manager_id = document.getElementById('manager_id').value;

            return new Promise((resolve, reject) => {
                const holidaysTakenThisYear = await getHolidaysTaken(hiredDate);
                const employeeHiredDate = await getEmployeeHiredDate(id);
                const remaining = calculateRemainingHolidays(employeeHiredDate, holidaysTakenThisYear);
                startDate = document.getElementById(`start_date-${id}`).value;
                endDate = document.getElementById(`end_date-${id}`).value;

                const difference = calculateDifferences(new Date(startDate), new Date(endDate) ).dayDifference;

                console.log('remaining', remaining);

                if (remaining<difference){
                    // approve with function
                    // approveHoliday(id, manager_id);
                }else{
                    alert('Not enough holidays remaining');
                }

                resolve(true);
            });
        }*/



        async function declineHolidayRequest (id){

            const manager_id = document.getElementById('manager_id').value;

            return new Promise((resolve, reject) => {
                formData = new FormData();
                // formData.append('manager_id', manager_id);
                formData.append('holiday_id', id);
                formData.append('func', 'declineHolidayRequest');

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions3.php', true);
                xhr.onload = function () {
                    const response = xhr.responseText;
                    console.log(response);
                    resolve(response);
                };
                xhr.onerror = function () {
                    reject('Error checking employee existence');
                };
                xhr.send(formData);
            });

        }

        /***** END OF FUNCTIONS ******/

        document.getElementById('manager-form').addEventListener('submit', function (event) {
            event.preventDefault();
            const managerId = document.getElementById('manager_id').value;
            const outcome = getHolidayRequests();
            console.log(outcome);
        });

        requests = document.getElementById('requests');

        requests.addEventListener('click', async function (event) {
            const id = event.target.id;
            const arr = id.split("-");
            console.log("arrraaayy", arr)
            const holiday_id = Number(arr[arr.length - 1]);
            console.log(holiday_id);
            if (event.target.classList.contains('btn-primary')) {
                console.log('Approve button clicked', holiday_id);
                await approveHolidayRequest(holiday_id);
            } else if (event.target.classList.contains('btn-danger')) {
                console.log('Decline button clicked', holiday_id);
                await declineHolidayRequest(holiday_id);
                await getHolidayRequests();

            }
        });

    </script>
</body>

</html>