<?php
$id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./card.css">
    
    <title>Document</title>
</head>

<body>

    <?php include "components/navbar.php" ?>
    <h1>update</h1>

    <br><br>
    <h1 id = "status">Status:</h1>
    <button class="btn btn-primary" onclick="fetchPromotionInfo(<?php echo $id; ?>)">promote</button>
    <button class="btn btn-primary" onclick="fetchFormInfo(<?php echo $id; ?>)">update</button>

    <div id="target"></div>
    <hr>
    <div class="container d-flex justify-content-center align-items-center" id="target2"></div>


    <script>
        // JavaScript code goes here
        function createEmployeeCard(employeeData) {
            return `
            <div class="card">
                <div class="card-body">
                    <h2 id="${employeeData.id}">${employeeData.id}</h2>
                    <h3>${employeeData.Name}</h3>
                    
                    <p>Job Title: ${employeeData.Position}</p>
                    <img alt="no image found" src="data:image;base64,${employeeData.Image}">
                    <div class="contact-info">
                        <p>Email: <a href="mailto:${employeeData.Email}">${employeeData.Email}</a></p>
                    </div>
                    
                        <h4>Managed by:</h4>
                        ${employeeData.Manager_List.map(manager => `<p>${manager}</p>`).join('')}
                        
                        <p>Salary: ${employeeData.Salary}</->
                        <p>Date of Birth: ${employeeData.Date_of_Birth}</->
                        <p>Hired Date: ${employeeData.Hired_Date}</->
                        <p>Contract: ${employeeData.Contract}</->
                        <p>National Insurance Number: ${employeeData.National_Insurance_Number}</->
                        <p>Home Address: ${employeeData.Home_Address}</->
                        <p>Location Name: ${employeeData.Location_Name}</->
                        <p>Emergency Contact Name: ${employeeData.Emergency_Name}</->
                        <p>Emergency Contact Phone Number: ${employeeData.Emergency_Phone}</->
                        <p>Emergency Contact Relationship: ${employeeData.Emergency_Relationship}</->
                    
                </div>
            </div>`;

        }


        function fetchCardInfo(id) {
            const formData = new FormData()
            formData.append("func", "getCardInfo");
            formData.append("employee_id", id);


            for (const [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const xhr2 = new XMLHttpRequest();
            xhr2.open('POST', 'functions2_update.php', true);

            xhr2.onload = function () {
                if (xhr2.status === 200) {
                    const response = xhr2.responseText;
                    console.log("Response from server:", response);
                } else {
                    console.log('Request failed. Status: ' + xhr2.status);
                }
            };

            xhr2.onload = function () {
                // Process the response here
                // Assuming xhr.responseText contains the HTML code
                const targetDiv = document.getElementById("target2");
                targetDiv.innerHTML = createEmployeeCard(JSON.parse(xhr2.responseText));
            };
            xhr2.send(formData);
        }

        function updateEmployee() {
            const form = document.getElementById('update-employee-form');

            const formData = new FormData(form);
            formData.append('id', id);
            formData.append('func', 'updateEmployee');

            console.log("Update Employee    ", formData);
            //check if manager is empty
            if (formData.get('manager') === "") {
                // change manager to -1
                formData.set('manager', -1);
            }
            const xhr3 = new XMLHttpRequest();

            xhr3.open('POST', 'functions2_update.php', true);

            xhr3.onload = function () {
                const targetDiv = document.getElementById("target");
                if (xhr3.responseText !== "") {
                    console.log("UPDATEEEEE", xhr3.responseText);
                }
            };
            xhr3.onload = function () {
                if (xhr3.responseText === "success") {
                    document .getElementById('status').innerHTML = "Success";
                    fetchCardInfo(id); // Call fetchCardInfo only after the update query has been executed successfully
                } else {
                    document .getElementById('status').innerHTML = "Status: No manager exists";
                    console.log("Error: " + xhr3.responseText);
                    
                }
            };
            xhr3.send(formData);


        }

        function prefillForm(employeeData) {
            document.getElementById('name').value = employeeData.Name;
            document.getElementById('email').value = employeeData.Email;
            document.getElementById('contract').value = employeeData.Contract;
            document.getElementById('birth-date').value = employeeData.Date_of_Birth;
            document.getElementById('salary').value = employeeData.Salary;
            document.getElementById('national_insurance_number').value = employeeData.National_Insurance_Number;
            document.getElementById('address').value = employeeData.Home_Address;
            //document.getElementById('hired-date').value = employeeData.Hired_Date;
            document.getElementById('location').value = employeeData.Location_ID;
            document.getElementById('job-title').value = employeeData.Position;
            document.getElementById('emergency_name').value = employeeData.Emergency_Name;
            document.getElementById('emergency_number').value = employeeData.Emergency_Phone;
            document.getElementById('emergency_relationship').value = employeeData.Emergency_Relationship;
            if (employeeData.Manager_List_ID !== null) {
                if (employeeData.Manager_List_ID.length > 0) {
                    document.getElementById('manager').value = employeeData.Manager_List_ID[0];
                } else {
                    document.getElementById('manager').value = null;
                }

            } else {
                document.getElementById('manager').value = null;
                //document.getElementById('manager').setAttribute('readonly', 'readonly');
            }

        }

        function formHTML() {
            document.getElementById('target').innerHTML = `
            <div class="container mt-5">
            <form id="update-employee-form" method="post">

                <label for="name" class="form-label">Name:</label>
                <input id="name" required type="text" name="name" placeholder="Name" class="form-control">
                <br><br>
                <label for="email" class="form-label">Email:</label>
                <input id="email" required type="email" name="email" placeholder="Email" class="form-control">
                <br><br>
                <label for="contract" class="form-label">Contract:</label>
                <input id="contract" required type="text" name="contract" placeholder="Contract" class="form-control">
                <br><br>
                <label required for="birth-date" class="form-label">Brith Date:</label>
                <input id="birth-date" required type="date" name="birth-date" placeholder="Birth Date" class="form-control">
                <br><br>
                <label for="salary" class="form-label">Salary:</label>
                <input id="salary" required type="text" name="salary" placeholder="Salary" class="form-control">
                <br><br>
                <label for="national_insurance_number" class="form-label">national_insurance_number</label>
                <input id="national_insurance_number" required type="text" minlength="9" maxlength="9" name="national_insurance_number"
                    placeholder="National Insurance Number" class="form-control">
                <br><br>
                <label for="address" class="form-label">address:</label>
                <input id="address" required type="text" name="address" placeholder="Address" class="form-control">
                <br><br>
                <!--
                <label for="hired-date">hired:</label>
                <input id="hired-date" required type="date" name="hired-date" placeholder="Hired Date">
                -->
                <label for="location" class="form-label">Location:</label>

                <select name="location" id="location" class="form-select">
                    <option value="6">Silverstone</option>
                    <option value="7">Milton Keynes</option>
                    <option value="8">Silverstone</option>
                    <option value="9">Milton Keynes</option>
                    <option value="10">Nurseries Road</option>
                    <option value="11">Burnside Distribution Ltd</option>
                    <option value="12">Musgrave Channel Road</option>
                    <option value="13">Kilburn Building</option>
                    <option value="14">Lewis Building</option>
                    <option value="15">Broadgate Tower</option>
                    <option value="16">Navigation St</option>
                    <option value="17">Wentloog Corporate Park</option>
                    <option value="18">FPS Distribution Ltd</option>
                </select>
                <br><br>
                <label for="positions" class="form-label">Positions:</label>
                <select required name="job-title" id="job-title" class="form-select">
                    <!-- CEO and PA (No Department) -->
                    <optgroup label="No Department">
                        <option value="CEO">CEO</option>
                        <option value="PA">Personal Assistant (PA)</option>
                    </optgroup>

                    <!-- Operations Department -->
                    <optgroup label="Operations (COO)">
                        <option value="Health & Safety Officer">Health & Safety Officer</option>
                        <option value="Factory Worker">Factory Worker</option>
                        <option value="Delivery Driver">Delivery Driver</option>
                    </optgroup>

                    <!-- Finance Department -->
                    <optgroup label="Finance (CFO)">
                        <option value="Accountant">Accountant</option>
                        <option value="Financial Analyst">Financial Analyst</option>
                    </optgroup>

                    <!-- Technology Department -->
                    <optgroup label="Technology (CTO)">
                        <option value="Front End Developer">Front End Developer</option>
                        <option value="Back End Developer">Back End Developer</option>
                        <option value="Full Stack Developer">Full Stack Developer</option>
                        <option value="Junior Developer">Junior Developer</option>
                        <option value="Cyber Security">Cyber Security</option>
                    </optgroup>

                    <!-- Marketing Department -->
                    <optgroup label="Marketing (CMO)">
                        <option value="Brand Developer">Brand Developer</option>
                        <option value="Industry Researcher">Industry Researcher</option>
                        <option value="Product Designer">Product Designer</option>
                    </optgroup>
                </select>
                <br><br>
                <label for="image" class="form-label">Change Image:</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-control">
                <br><br>
                <label for="manager" class="form-label">Manager id:</label>
                <input type="number" name="manager" id="manager" class="form-control">
                <br><br>
                <label for="emergency_name" class="form-label">Emergency Contact Name</label>
                <input type="text" name="emergency_name" id="emergency_name" placeholder="Emergency Contact Name" class="form-control">
                <br><br>
                <label for="emergency_number" class="form-label">Emergency Contact Number</label>
                <input type="text" name="emergency_number" id="emergency_number" placeholder="Emergency Contact Number" class="form-control">
                <br><br>
                <label for="emergency_relationship" class="form-label">Emergency Contact Relationship</label>
                <input type="text" name="emergency_relationship" id="emergency_relationship" placeholder="Emergency Contact Relationship" class="form-control">
                <br><br>

                <input type="submit" value="Update" class="btn btn-primary">
            </form>
            </div>

            `;
            document.getElementById('update-employee-form').addEventListener('submit',
                (e) => {
                    e.preventDefault();
                    updateEmployee();
                }
            );
        }

        function fetchFormInfo(id) {
            const formData = new FormData()
            formData.append("func", "getCardInfo");
            formData.append("employee_id", id);

            for (const [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const xhr2 = new XMLHttpRequest();
            xhr2.open('POST', 'functions2_update.php', true);

            xhr2.onload = function () {
                formHTML();
                prefillForm(JSON.parse(xhr2.responseText));
            };
            
            xhr2.send(formData);
        }

        function promoteEmployee() {
            const form = document.getElementById('promote-employee-form');

            const formData = new FormData(form);
            formData.append('id', id);
            formData.append('func', 'promoteEmployee');

            console.log("Update Employee ", formData);
            const xhr3 = new XMLHttpRequest();

            xhr3.open('POST', 'functions2_update.php', true);

            xhr3.onload = function () {
                const targetDiv = document.getElementById("target");
                if (xhr3.responseText !== "") {
                    console.log("PROMMMOOTTEEE", xhr3.responseText);
                }
            };

            xhr3.onload = function () {
                if (xhr3.status === 200) {
                    const response = xhr3.responseText;
                    console.log("Response from server:", response);
                    fetchCardInfo(id);
                    fetchPromotionInfo(id);
                } else { console.log('Request failed. Status: ' + xhr3.status); }
            };
            xhr3.send(formData);
            
        }

        function prefillPromotion(employeeData) {
            document.getElementById('name').value = employeeData.Name;
            document.getElementById('email').value = employeeData.Email;
            document.getElementById('salary').value = employeeData.Salary;
            document.getElementById('contract').value = employeeData.Contract;
            document.getElementById('job-title').value = employeeData.Position;
            document.getElementById('promotion_percentage').value = 10;

        }

        function promotionHTML() {
            document .getElementById('status').innerHTML = "";
            document.getElementById('target').innerHTML = `
            <div class = "container">
            <h1>Promote an Employee</h1>
            <form id="promote-employee-form" method="post">

                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input id="name" required type="text" name="name" placeholder="Name" readonly class="form-control">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input id="email" required type="email" name="email" placeholder="Email" readonly class="form-control">
                </div>

                <div class="mb-3">
                    <label for="salary" class="form-label">Salary:</label>
                    <input id="salary" required type="number" name="salary" placeholder="Salary" readonly class="form-control">
                </div>

                <div class="mb-3">
                    <label for="contract" class="form-label">Contract:</label>
                    <input id="contract" required type="text" name="contract" placeholder="Contract" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="positions" class="form-label">Positions:</label>
                    <select required name="job-title" id="job-title" class="form-select">
                        <!-- CEO and PA (No Department) -->
                        <optgroup label="No Department">
                            <option value="CEO">CEO</option>
                            <option value="PA">Personal Assistant (PA)</option>
                        </optgroup>

                        <!-- Operations Department -->
                        <optgroup label="Operations (COO)">
                            <option value="Health & Safety Officer">Health & Safety Officer</option>
                            <option value="Factory Worker">Factory Worker</option>
                            <option value="Delivery Driver">Delivery Driver</option>
                        </optgroup>

                        <!-- Finance Department -->
                        <optgroup label="Finance (CFO)">
                            <option value="Accountant">Accountant</option>
                            <option value="Financial Analyst">Financial Analyst</option>
                        </optgroup>

                        <!-- Technology Department -->
                        <optgroup label="Technology (CTO)">
                            <option value="Front End Developer">Front End Developer</option>
                            <option value="Back End Developer">Back End Developer</option>
                            <option value="Full Stack Developer">Full Stack Developer</option>
                            <option value="Junior Developer">Junior Developer</option>
                            <option value="Cyber Security">Cyber Security</option>
                        </optgroup>

                        <!-- Marketing Department -->
                        <optgroup label="Marketing (CMO)">
                            <option value="Brand Developer">Brand Developer</option>
                            <option value="Industry Researcher">Industry Researcher</option>
                            <option value="Product Designer">Product Designer</option>
                        </optgroup>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="promotion_percentage" class="form-label">Promotion Percentage:</label>
                    <input id="promotion_percentage" name="promotion_percentage" min="1" max="100" placeholder="Promotion Percentage" required type="number" value="10" step="0.1" class="form-control">
                </div>
                
                <input type="submit" value="Promote" class="btn btn-primary">
            </form>

            </div>
            `;
            document.getElementById('promote-employee-form').addEventListener('submit',
                (e) => {
                    console.log('Promotion form submitted!');
                    // debugger;
                    event.preventDefault();
                    promoteEmployee();
                    
                }
            );
        }



        function fetchPromotionInfo(id) {
            const formData = new FormData()
            formData.append("func", "getCardInfo");
            formData.append("employee_id", id);

            for (const [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            const xhr2 = new XMLHttpRequest();
            xhr2.open('POST', 'functions2_update.php', true);

            xhr2.onload = function () {
                promotionHTML();
                console.log("Promotion reload", xhr2.responseText);
                prefillPromotion(JSON.parse(xhr2.responseText));
            };
            xhr2.send(formData);
        }

        function toggleAdditionalInfo(event) {
            if (event.target.classList.contains('toggle-additional-info')) {
                const button = event.target;
                const card = button.closest('.card');
                const additionalInfo = card.querySelector('.additional-info');
                additionalInfo.classList.toggle('show');
                button.textContent = additionalInfo.classList.contains('show') ? 'Hide' : 'Show More';
            }
        }



        var id = '<?php echo $id; ?>';
        fetchFormInfo(id);
        const cardsContainer = document.getElementById('target2');
        cardsContainer.addEventListener('click', toggleAdditionalInfo);



    </script>
</body>

</html>