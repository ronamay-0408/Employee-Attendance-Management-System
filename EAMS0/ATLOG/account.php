<?php
session_start();

// Assuming you have a MySQL database connection established
$host = "localhost";
$user = "root";
$password = "";
$databasename = "eams";

$conn = mysqli_connect($host, $user, $password, $databasename);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if session data exists
if (isset($_SESSION['user_data'])) {
    // Retrieve user data from the session
    $userData = $_SESSION['user_data'];

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
    <title>Welcome Page</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0; /* Remove default body margin */
            overflow: hidden; /* Hide body overflow */
        }

        .header {
            background-color: #2196f3;
            color: white;
            font-size: 24px;
            padding: 15px;
            text-align: center;
            font-weight: 700;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 25%;
            height: 100vh;
            overflow-y: auto;
            background-color: #333;
            color: white;
            list-style: none;
            font-weight: 500;
        }

        .sidebar li {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #555;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sidebar li:hover {
            background-color: #555;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            display: flex;
            margin-left: 15px;
        }

        .sidebar i,
        .dropdown-btn i {
            margin-right: 8px;
        }

        .dropdown-btn {
            padding: 15px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            outline: none;
            transition: background-color 0.3s;
        }

        .dropdown-btn:hover {
            background-color: #555;
        }

        .dropdown-container {
            display: none;
            background-color: #262626;
        }

        .dropdown-container a {
            text-decoration: none;
            color: white;
            display: block;
            transition: background-color 0.3s;
        }

        .dropdown-container a:hover {
            background-color: #555;
        }

        .dropdown-btn .fa {
            margin-left: 1.5rem;
        }

        .main {
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            padding: 50px;
            background-image: url(https://wallpaperaccess.com/full/744706.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .table-container {
            position: relative;
            margin-bottom: 20px;
        }

        .title {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #f5f5f596;
        }

        .action-bar {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .welcome-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }
        .logout_btn {
            font-size: 25px;
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
            color: white;
        }

        form {
            margin-top: 20px;
        }
    
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
    
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    
        input[readonly] {
            background-color: #eee;
        }
    
        .firstline {
            display: flex;
            margin-top: 10rem;
        }
    
        .firstline label {
            margin-top: 0.7rem;
            margin-right: 0.5rem;
        }
    
        .firstline input {
            margin-right: 0.5rem;
            height: 40px;
        }
    
        .secondline {
            display: flex;
        }
    
        .secondline label {
            margin-top: 0.7rem;
            margin-right: 0.5rem;
        }
    
        .secondline input {
            margin-right: 0.5rem;
        }
    
        .btn-container {
            display: flex;
            justify-content: center;
            /* Center the button within the container */
        }
    
        .btn {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #2196f3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    
        .image {
            margin-top: -10rem;
        }
    </style>
</head>
<body>
    <div class='header'>ACCOUNT INFORMATION</div>
    <div class='logout_btn'><i class='fa-solid fa-right-from-bracket'></i></div>
    <div class='container'>
        <ul class='sidebar'>
            <li><a href='EAM_Dashboard_Emp.html'><i class='fa-solid fa-house'></i> Dashboard</a></li>
            <li><a href='myattendance.php'><i class='fa-solid fa-clipboard-user'></i> My Attendance</a></li>
            <li><a href='account.php'><i class='fa-solid fa-user'></i> Account Information</a></li>
            <li class='dropdown-btn'><a><i class='fa-solid fa-camera'></i> Capture Attendance <i class='fa fa-caret-down'></i></a></li>
            <div class='dropdown-container'>
                <li><a href='am_timein.php'><i class='fa-solid fa-sun'></i>AM Time-in</a></li>
                <li><a href='am_timeout.php'><i class='fa-solid fa-sun'></i>AM Time-out</a></li>
                <li><a href='pm_timein.php'><i class='fa-solid fa-cloud-sun'></i>PM Time-in</a></li>
                <li><a href='pm_timeout.php'><i class='fa-solid fa-cloud-sun'></i></i>PM Time-out</a></li>
            </div>
        </ul>
        <div class='main'>
            <div class='table-container'>
                <div class='action-bar'>
                    <div class='welcome-container'>
                    <form action='db.php' method='POST' enctype='multipart/form-data'>
                        <input type='hidden' name='emp_id' value='{$userData['Id']}'>
                        <div class='firstline'>
                            <label for='code'>Code:</label>
                            <input type='text' name='code' value='{$userData['Code']}' class='form-control' readonly>

                            <label for='idNumber'>ID_Number:</label>
                            <input type='text' name='id_number' value='{$userData['IDNumber']}' class='form-control' readonly>

                            <div class='image'>
                                <input type='image' name='avatar' value='img' src='../Photo/{$userData['Avatar']}' alt='Avatar' style='width: 200px; height: 200px;' readonly>
                            </div>
                        </div>

                        <label for='fullName'>Full Name:</label>
                        <input type='text' name='full_name' value='{$userData['FullName']}' class='form-control' readonly>

                        <label for='address'>Address:</label>
                        <input type='text' name='address' value='{$userData['Address']}' class='form-control' readonly>

                        <label for='phoneNumber'>Phone Number:</label>
                        <input type='text' name='phone_number' value='{$userData['PhoneNumber']}' class='form-control' readonly>

                        <label for='email'>Email:</label>
                        <input type='email' name='email' value='{$userData['Email']}' class='form-control' readonly>

                        <div class='secondline'>
                            <label for='sex'>Sex:</label>
                            <input type='text' name='sex' value='{$userData['Sex']}' class='form-control' readonly>

                            <label for='dateOfBirth'>Date_of_Birth:</label>
                            <input type='date' name='date_of_birth' value='{$userData['DateOfBirth']}' class='form-control' readonly>
                        </div>

                        <label for='placeOfBirth'>Place of Birth:</label>
                        <input type='text' name='place_of_birth' value='{$userData['PlaceOfBirth']}' class='form-control' readonly>

                        <label for='employeePosition'>Employee Position:</label>
                        <input type='text' name='employee_position' value='{$userData['EmployeePosition']}' class='form-control' readonly>

                        <label for='department'>Department:</label>
                        <input type='text' name='department' value='{$userData['Department']}' class='form-control' readonly>

                        <label for='dateOfHire'>Date of Hire:</label>
                        <input type='date' name='date_of_hire' value='{$userData['DateOfHire']}' class='form-control' readonly>

                        <label for='employeeStatus'>Employee Status:</label>
                        <input type='text' name='employee_status' value='{$userData['EmployeeStatus']}' class='form-control' readonly>

                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

        // Attach the logout function to the click event of the logout_btn
        document.querySelector('.logout_btn').addEventListener('click', logout);

        //* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
        var dropdown = document.getElementsByClassName('dropdown-btn');
        var i;

        for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener('click', function() {
            this.classList.toggle('active');
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
            } else {
            dropdownContent.style.display = 'block';
            }
        });
        }
    </script>
</body>
</html>";

    // Clear the session data if it's empty
    if (empty($_SESSION)) {
        // If no session data exists, redirect to the login page
        header("Location:../index.php");
        exit();
    }
} else {
    // If session data is not found, redirect to the login page
    header("Location: ../index.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
