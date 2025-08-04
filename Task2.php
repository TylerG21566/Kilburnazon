<form?php // PHP code goes here ?>


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

        <br>
        <div class="container align-items-center d-flex justify-content-center" id="target"></div>
        <hr>
        <form id="add-employee-form" method="post" class="container mt-4">

    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input id="name" required type="text" name="name" class="form-control" placeholder="Name" maxlength="100">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input id="email" required type="email" name="email" class="form-control" placeholder="Email" maxlength="100">
    </div>

    <div class="mb-3">
        <label for="contract" class="form-label">Contract:</label>
        <input id="contract" required type="text" name="contract" class="form-control" placeholder="Contract"
            maxlength="100">
    </div>

    <div class="mb-3">
        <label for="birth-date" class="form-label">Birth Date:</label>
        <input id="birth-date" max="<?php echo date('Y-m-d'); ?>" required type="date" name="birth-date"
            class="form-control" placeholder="Birth Date">
    </div>

    <div class="mb-3">
        <label for="salary" class="form-label">Salary:</label>
        <input id="salary" required type="number" name="salary" class="form-control" placeholder="Salary" min="0"
            max="1000000000000">
    </div>

    <div class="mb-3">
        <label for="national_insurance_number" class="form-label">National Insurance Number:</label>
        <input id="national_insurance_number" required type="text" minlength="9" maxlength="9"
            name="national_insurance_number" class="form-control" placeholder="National Insurance Number">
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address:</label>
        <input id="address" required type="text" max="100" name="address" class="form-control" placeholder="Address">
    </div>

    <div class="mb-3">
        <label for="hired-date" class="form-label">Hired Date:</label>
        <input type="date" id="hired-date" name="hired-date" max="<?php echo date('Y-m-d'); ?>" required
            class="form-control" placeholder="Hired Date">
    </div>

    <div class="mb-3">
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
    </div>

    <div class="mb-3">
        <label for="positions" class="form-label">Positions:</label>
        <select required name="job-title" id="job-title" class="form-select">
            <optgroup label="No Department">
                <option value="CEO">CEO</option>
                <option value="PA">Personal Assistant (PA)</option>
            </optgroup>
            <optgroup label="Operations">
                <option value="COO">COO</option>
                <option value="Health & Safety Officer">Health & Safety Officer</option>
                <option value="Factory Worker">Factory Worker</option>
                <option value="Delivery Driver">Delivery Driver</option>
            </optgroup>
            <optgroup label="Finance">
                <option value="CFO">CFO</option>
                <option value="Accountant">Accountant</option>
                <option value="Financial Analyst">Financial Analyst</option>
            </optgroup>
            <optgroup label="Technology">
                <option value="CTO">CTO</option>
                <option value="Front End Developer">Front End Developer</option>
                <option value="Back End Developer">Back End Developer</option>
                <option value="Full Stack Developer">Full Stack Developer</option>
                <option value="Junior Developer">Junior Developer</option>
                <option value="Cyber Security">Cyber Security</option>
            </optgroup>
            <optgroup label="Marketing">
                <option value="CMO">CMO</option>
                <option value="Brand Developer">Brand Developer</option>
                <option value="Industry Researcher">Industry Researcher</option>
                <option value="Product Designer">Product Designer</option>
            </optgroup>
        </select>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Upload Image:</label>
        <input required type="file" id="image" name="image" accept="image/*" class="form-control">
    </div>

    <div class="mb-3">
        <label for="manager" class="form-label">Manager ID:</label>
        <input type="number" min="0" name="manager" id="manager" class="form-control">
    </div>

    <div class="mb-3">
        <label for="emergency_name" class="form-label">Emergency Contact Name:</label>
        <input type="text" name="emergency_name" id="emergency_name" class="form-control"
            placeholder="Emergency Contact Name">
    </div>

    <div class="mb-3">
        <label for="emergency_number" class="form-label">Emergency Contact Number:</label>
        <input type="text" name="emergency_number" id="emergency_number" class="form-control"
            placeholder="Emergency Contact Number">
    </div>

    <div class="mb-3">
        <label for="emergency_relationship" class="form-label">Emergency Contact Relationship:</label>
        <input type="text" name="emergency_relationship" id="emergency_relationship" class="form-control"
            placeholder="Emergency Contact Relationship">
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>



        <script>

            function calculateDifferences(startDateStr, endDateStr) {
                const startDate = new Date(startDateStr);
                const endDate = new Date(endDateStr);

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
                    <button class="toggle-additional-info">Show More</button>
                    <a href="./Task2_up.php?id=${employeeData.ID}"><button class="btn btn-primary">update</button></a>
                    <div class="additional-info">
                        <h4>Managed by:</h4>
                        <ul>${employeeData.Manager_List.map(manager => `<li>${manager}</li>`).join('')}</ul>
                        
                        <h4>Salary: ${employeeData.Salary}</h4>
                        <h4>Date of Birth: ${employeeData.Date_of_Birth}</h4>
                        <h4>Hired Date: ${employeeData.Hired_Date}</h4>
                        <h4>Contract: ${employeeData.Contract}</h4>
                        <h4>National Insurance Number: ${employeeData.National_Insurance_Number}</h4>
                        <h4>Home Address: ${employeeData.Home_Address}</h4>
                        <h4>Location Name: ${employeeData.Location_Name}</h4>
                        <h4>Emergency Contact Name: ${employeeData.Emergency_Name}</h4>
                        <h4>Emergency Contact Phone Number: ${employeeData.Emergency_Phone}</h4>
                        <h4>Emergency Contact Relationship: ${employeeData.Emergency_Relationship}</h4>
                  
                    </div>
                </div>
            </div>`;
            }


            function fetchCardInfo(id, location_name, location_id) {
                const formData = new FormData()
                formData.append("func", "getCardInfo");
                formData.append("employee_id", id);
                formData.append("location_n", location_name);
                formData.append("location_id", location_id);

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
                    const targetDiv = document.getElementById("target");
                    targetDiv.innerHTML = createEmployeeCard(JSON.parse(xhr2.responseText));
                };
                xhr2.send(formData);
            }

            function addEmployee(e) {
                console.log("add employee");
                e.preventDefault();
                const name = document.getElementById("name").value;
                const email = document.getElementById("email").value;
                const contract = document.getElementById("contract").value;
                const jobTitle = document.getElementById("job-title").value;
                const location = document.getElementById("location").value;
                const Location_Name = document.getElementById("location").options[document.getElementById("location").selectedIndex].text;
                const hiredDate = document.getElementById("hired-date").value;
                const salary = document.getElementById("salary").value;
                const address = document.getElementById("address").value;
                const birthDate = document.getElementById("birth-date").value;
                const nationalInsuranceNumber = document.getElementById("national_insurance_number").value;
                let manager = document.getElementById("manager").value;
                const emergencyContactName = document.getElementById("emergency_name").value;
                const emergencyContactNumber = document.getElementById("emergency_number").value;
                const emergencyContactRelationship = document.getElementById("emergency_relationship").value;

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'functions2_addEmployee.php', true);
                // xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                const formData = new FormData();
                formData.append("name", name);
                formData.append("email", email);
                formData.append("contract", contract);
                formData.append("job-title", jobTitle);
                formData.append("location", location);
                formData.append("Location_Name", Location_Name);
                formData.append("hired-date", hiredDate);
                formData.append("salary", salary);
                formData.append("address", address);
                formData.append("birth-date", birthDate);
                formData.append("national_insurance_number", nationalInsuranceNumber);
                formData.append("emergency_contact_name", emergencyContactName);
                formData.append("emergency_contact_phone", emergencyContactNumber);
                formData.append("emergency_contact_relationship", emergencyContactRelationship);


                // if manager empty
                if (manager === "") {
                    manager = -1;
                }

                formData.append("manager", manager);

                // Add image data as a part of the form data
                const imageQ = document.querySelector("#image");
                formData.append("image", imageQ.files[0]);
                console.log("hi");

                xhr.onload = function () {
                    // ... (existing success handling
                    const targetDiv = document.getElementById("target");
                    targetDiv.innerHTML = xhr.responseText;

                    latest_index = Number(xhr.responseText);

                    if (isNaN(latest_index)) {
                        latest_index = -1;
                        console.log("error in server NAN");
                    } else {
                        if (latest_index === -1) {
                            console.log("error in server");
                        } else {
                            console.log(`success ${latest_index}`);
                            if (!(latest_index === -1 || latest_index === 0)) {
                                fetchCardInfo(latest_index, Location_Name, location);
                            }
                        }
                        console.log(xhr.responseText);
                    }
                };
                console.log(formData)

                console.log(imageQ.files[0]);

                xhr.send(formData);
                //fetchCardInfo(latest_index, Location_Name, location);
            }

            const jobTitles = {
                "Finance": ["Accountant", "Financial Analyst"],
                "Operations": ["Health & Safety Officer", "Factory Worker", "Delivery Driver"],
                "Marketing": ["Brand Developer", "Industry Researcher", "Product Designer"],
                "Technology": ["Front End Developer", "Back End Developer", "Full Stack Developer", "Junior Developer", "Cyber Security"]
            };

            let latest_index = -1;

            // document.getElementById('add-employee-form').addEventListener('change', validateForm);

            document.getElementById('add-employee-form').addEventListener('submit', addEmployee);
            //document.getElementById('add-employee-form').addEventListener('change', addEmployee);

            function toggleAdditionalInfo(event) {
                if (event.target.classList.contains('toggle-additional-info')) {
                    const button = event.target;
                    const card = button.closest('.card');
                    const additionalInfo = card.querySelector('.additional-info');
                    additionalInfo.classList.toggle('show');
                    button.textContent = additionalInfo.classList.contains('show') ? 'Hide' : 'Show More';
                }
            }

            // Add event listener to the parent element that contains the cards
            const cardsContainer = document.getElementById('target');
            cardsContainer.addEventListener('click', toggleAdditionalInfo);


        </script>

    </body>

    </html>