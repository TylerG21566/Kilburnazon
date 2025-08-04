<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <?php
    include "components/navbar.php";
    ?>
    <div class = "container mt-5">
    <h1>birthday</h1>
    <button class="btn btn-primary" onclick="getBirthdays()">This Month's BIRTHDAYS!</button>
    <p id="birthdays"></p>
    </div>
    <script>
        function getBirthdays() {
            
            console.log("hello");
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'functions5.php', true);
            const formData = new FormData();
            formData.append('func', 'getBirthdays');
            xhr.onload = function () {
                const response = xhr.responseText;
                console.log(response);
                document.getElementById('birthdays').innerHTML = response;
            };
            xhr.send(formData);
        }
    </script>
</body>
</html>