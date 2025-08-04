<?php

// Capture the URL path
/*
$name = $_POST['name'];
$department = $_POST['department'];
$jobTitle = $_POST['job-title'];
$location = $_POST['location'];
$hiredDate = $_POST['hired-date'];
*/
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

   

    <h1> Kilburnazon </h1>
    

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h2>Login</h2>
                <form id="login-form">
                    <div class="mb-3">
                        <label for="loginUsername" class="form-label">Username</label>
                        <input required name="username" min="5" max="40" type="text" class="form-control" id="loginUsername" placeholder="Enter username">
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input required name="password" min="5" max="40" type="password" class="form-control" id="loginPassword" placeholder="Password">
                    </div>
                    <button type="button" id="login-submit" class="btn btn-primary">Login</button>
                </form>
            </div>
            <div class="col-md-6">
                <h2>Sign Up</h2>
                <h5>status:</h5>
                <form id="signup-form">
                    <div class="mb-3">
                        <label for="signupUsername" class="form-label">Username</label>
                        <input required name="username" min="5" max="40" type="text" class="form-control" id="signupUsername" placeholder="Enter username">
                    </div>
                    <div class="mb-3">
                        <label for="EmployeeID" class="form-label">EmployeeID</label>
                        <input required name="employeeID" min="1000" max="999999999" type="number" class="form-control" id="EmployeeID" placeholder="Enter EmployeeID">
                    </div>
                    <div class="mb-3">
                        <label for="signupPassword" class="form-label">Password</label>
                        <input required name="password" min="5" max = "40" type="password" class="form-control" id="signupPassword" placeholder="Password">
                    </div>
                    <div class="mb-3">
                        <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
                        <input required name="passwordConfirm" min="5" max = "40" type="password" class="form-control" id="signupConfirmPassword" placeholder="Confirm password">
                    </div>
                    <button id="signup-submit" type="button" class="btn btn-success">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <script>

        async function signup() {
            form = document.getElementById('signup-form');
            formData = new FormData(form);
            formData.append('func', 'signup');

            try {
                const response = await fetch('signUpLogin.php', {
                method: 'POST',
                body: formData,
                headers: {
        'Accept': 'application/json'
    }
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    return data;
                })

                return response;
            } catch (error) {
                console.error('Error:', error);   
            }
        }

        async function login() {
            form = document.getElementById('login-form');
            formData = new FormData(form);
            formData.append('func', 'login');

            try {
                const response = await fetch('signUpLogin.php', {
                method: 'POST',
                body: formData,
                headers: {
        'Accept': 'application/json'
    }
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    return data;
                })               

                return response;
            } catch (error) {
                console.error('Error:', error);   
            }
        }

        document.getElementById('login-submit').addEventListener('click', async function(event) {
            const result = await login();
            if (result === 'successemp' || result === 'successexec') {
                await window.location.reload();
                if (result === 'successemp') {
                    window.location.href = 'Task3_book.php';
                } else {
                    window.location.href = 'Task1.php';
                }
            }else{
                alert(result);
                // alert('Login successful');
                
                
            }
        })
          

        document.getElementById('signup-submit').addEventListener('click', async function(event) {
            // event.preventDefault(); // Prevent page reload
            var password = document.getElementById('signupPassword').value;
            var confirmPassword = document.getElementById('signupConfirmPassword').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match. Please try again.');
                return;
            }

            const result = await signup();
            if (result === 'success') {
                alert('Signup successful');
                
                // window.location.href = 'index.php';
            }else{
                alert(result);
            }
            
        });

        

    </script>
</body>

</html>