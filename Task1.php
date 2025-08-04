<?php

// Capture the URL path

$name = $_POST['name'];
$department = $_POST['department'];
$jobTitle = $_POST['job-title'];
$location = $_POST['location'];
$hiredDate = $_POST['hired-date'];


session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Retrieve user details from session variables
$username = $_SESSION['username'];
$employeeID = $_SESSION['E_ID'];
$privileges = $_SESSION['Privs'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Document</title>
  <link rel="stylesheet" href="./card.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <?php include "components/navbar.php" ?>
  
  <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <p>Your Employee ID: <?php echo htmlspecialchars($employeeID); ?></p>
    <p>Your Privileges: <?php echo htmlspecialchars($privileges); ?></p>
    <div class="container py-4">  
  <h1 class="mb-4">Search</h1>

  <form onchange="searchAndFilter()" onsubmit="searchAndFilter()" method="post">
    <div class="row g-3">
      <!-- Search Input -->
      <div class="col-md-6">
        <div class="form-floating">
          <input id="search" type="text" name="search" class="form-control" placeholder="Search Name...">
          <label for="search">Search Name</label>
        </div>
      </div>

      <!-- Employee ID Input -->
      <div class="col-md-6">
        <div class="form-floating">
          <input id="Employee_ID" type="text" name="Employee_ID" class="form-control" placeholder="Employee ID">
          <label for="Employee_ID">Employee ID</label>
        </div>
      </div>

      <!-- Department Select -->
      <div class="col-md-6">
        <div class="form-floating">
          <select id="department-select" name="department" class="form-select">
            <option value="">All Departments</option>
            <option value="Finance">Finance</option>
            <option value="Marketing">Marketing</option>
            <option value="Technology">Technology</option>
            <option value="Operations">Operations</option>
          </select>
          <label for="department-select">Department</label>
        </div>
      </div>

      <!-- Location Select -->
      <div class="col-md-6">
        <div class="form-floating">
          <select name="location-select" id="location-select" class="form-select">
            <option selected value="">All Job Locations</option>
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
          <label for="location-select">Location</label>
        </div>
      </div>

      <!-- Job Title Select -->
      <div class="col-md-6">
        <div class="form-floating">
          <select name="jobTitle" id="jobTitle" class="form-select">
            <option selected value="">All Job Titles</option>
            <optgroup label="No Department">
              <option value="CEO">CEO</option>
              <option value="PA">Personal Assistant (PA)</option>
            </optgroup>
            <!-- Operations Department -->
            <optgroup label="Operations">
              <option value="COO">COO</option>
              <option value="Health & Safety Officer">Health & Safety Officer</option>
              <option value="Factory Worker">Factory Worker</option>
              <option value="Delivery Driver">Delivery Driver</option>
            </optgroup>
            <!-- Finance Department -->
            <optgroup label="Finance">
              <option value="CFO">CFO</option>
              <option value="Accountant">Accountant</option>
              <option value="Financial Analyst">Financial Analyst</option>
            </optgroup>
            <!-- Technology Department -->
            <optgroup label="Technology">
              <option value="CTO">CTO</option>
              <option value="Front End Developer">Front End Developer</option>
              <option value="Back End Developer">Back End Developer</option>
              <option value="Full Stack Developer">Full Stack Developer</option>
              <option value="Junior Developer">Junior Developer</option>
              <option value="Cyber Security">Cyber Security</option>
            </optgroup>
            <!-- Marketing Department -->
            <optgroup label="Marketing">
              <option value="CMO">CMO</option>
              <option value="Brand Developer">Brand Developer</option>
              <option value="Industry Researcher">Industry Researcher</option>
              <option value="Product Designer">Product Designer</option>
            </optgroup>
          </select>
          <label for="jobTitle">Position</label>
        </div>
      </div>

      <!-- Date Input -->
      <div class="col-md-6">
        <div class="form-floating">
          <input type="date" name="hired-date-input" id="hired-date-input" class="form-control">
          <label for="hired-date-input">Start Date</label>
        </div>
      </div>


    </div>
  </form>
</div>

  <div class="directory" id="target"></div>


  <script>

    function createEmployeeCard(employee) {
      return `
        <div class="card">
            <div class="card-body">
                <h2 id="${employee.Employee_ID}">${employee.Employee_ID}</h2>
                <h3>${employee.name}</h3>
                <p>Department: ${employee.department}</p>
                <p>Job Title: ${employee.Position}</p>
                <img alt="no image found" src="data:image;base64,${employee.image}">
                <div class="contact-info">
                    <p>Email: <a href="mailto:${employee.email}">${employee.email}</a></p>
                </div>
                <button class="toggle-additional-info">Show More</button>
                <a href="./Task2_up.php?id=${employee.Employee_ID}"><button class="btn btn-primary">update</button></a>
                
                <div class="additional-info"">
                    <h4>Managed by:</h4>
                    <ul>${employee.managed_by}</ul>
                    <h4>Emergency Name:${employee.emergency_name}</h4>
                    <h4>Emergency Phone:${employee.emergency_phone}</h4>
                    <h4>Emergency Relationship:${employee.emergency_relationship}</h4>
                    <h4>Salary: ${employee.salary}</h4>
                    <h4>Date of Birth: ${employee.date_of_birth}</h4>
                    <h4>Hired Date: ${employee.hired_date}</h4>
                    <h4>Contract: ${employee.contract}</h4>
                    <h4>National Insurance Number: ${employee.national_insurance_number}</h4>
                    <h4>Home Address: ${employee.home_address}</h4>
                    <h4>Location ID: ${employee.Location_ID}</h4>
                    <h4>Location Name: ${employee.Location_Name}</h4>
                    <h4>Location Address: ${employee.Location_Address}</h4>
                    <h4>Location Phone: ${employee.Location_Phone}</h4>
                </div>
            </div>
        </div>
    `;
    }


    async function searchAndFilter() {

      const cardsContainer = document.getElementById('target');
      cardsContainer.addEventListener('click', toggleAdditionalInfo);
      formData = new FormData();

      formData.append('name', document.getElementById('search').value);
      formData.append('department', document.getElementById('department-select').value);
      formData.append('position', document.getElementById('jobTitle').value);
      formData.append('location', document.getElementById('location-select').value);
      formData.append('hired_date', document.getElementById('hired-date-input').value);
      formData.append('Employee_ID', document.getElementById('Employee_ID').value);

      formData.append('func', 'searchEmployeeCards');

      xhr = new XMLHttpRequest();
      xhr.open('POST', 'functions.php', true);
      xhr.onload = function () {
        const response = xhr.responseText;
        console.log(response);
        if (response === 'fail' || response === "" || response === "[]") {
          document.getElementById('target').innerHTML = `<h2>No employees found</h2>`;
        } else {
          console.log(response);
          document.getElementById('target').innerHTML = '';
          var jsonArray = JSON.parse(response);

          for (var i = 0; i < jsonArray.length; i++) {
            document.getElementById('target').innerHTML += createEmployeeCard(jsonArray[i]);
          }
        }
      };
      xhr.send(formData);
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

    const cardsContainer = document.getElementById('target');
    cardsContainer.addEventListener('click', toggleAdditionalInfo);

    // Search event listenter
    const searchInput = document.getElementById("search");
    searchInput.addEventListener('input', searchAndFilter);

    // Filter event listenter
    const filterSelect = document.getElementById("department-select");
    filterSelect.addEventListener('change', searchAndFilter);

    // Filter event listenter
    const jobTitleSelect = document.getElementById("jobTitle");
    jobTitleSelect.addEventListener('change', searchAndFilter);

    // Filter event listenter
    const locationSelect = document.getElementById("location-select");
    locationSelect.addEventListener('change', searchAndFilter);

    // Filter event listenter
    const startDateInput = document.getElementById("hired-date-input");
    startDateInput.addEventListener('change', searchAndFilter);

    // Filter event listenter
    const employeeIDInput = document.getElementById("Employee_ID");
    employeeIDInput.addEventListener('input', searchAndFilter);




    const jobTitles = {
      "Finance": ["Accountant", "Financial Analyst"],
      "Operations": ["Health & Safety Officer", "Factory Worker", "Delivery Driver"],
      "Marketing": ["Brand Developer", "Industry Researcher", "Product Designer"],
      "Technology": ["Front End Developer", "Back End Developer", "Full Stack Developer", "Junior Developer", "Cyber Security"]
    };


    const departmentSelect = document.getElementById('department-select');

    departmentSelect.addEventListener('change', function () {
      const selectedDepartment = departmentSelect.value;
      const jobTitlesForDepartment = jobTitles[selectedDepartment];
    });


  </script>
</body>

</html>