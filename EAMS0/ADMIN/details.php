<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
    <title>Employee Details</title>
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
            display: flex;
            justify-content: space-between;
            background-color: #2196f3;
            color: white;
            font-size: 24px;
            padding: 15px;
            text-align: center;
            font-weight: 700;
        }
        .header h3{
            margin: 0 auto; /* Center the h3 element horizontally */
        }

        .header i{
            color: white;
        }
        .container {
            display: flex;
        }

        /** DROPDOWN LOGOUT BUTTON*/
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
        /** DROPDOWN LOGOUT BUTTON*/

        .main {
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            padding: 20px;
            background-color: black;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .title {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #f5f5f596;
        }

        .welcome-container {
            width: 1100px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 60px;
        }

        h2 {
            color: #333;
            text-align: center;
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

        .logout_btn {
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
            color: white;

            overflow-y: auto;
            list-style: none;
            font-weight: 500;
        }
        .firstline{
            display: flex;
            margin-top: 10rem;
        }
        .firstline label{
            margin-top: 0.7rem;
            margin-right: 0.5rem;
        }
        .firstline input{
            margin-right: 0.5rem;
            height: 40px;

        }
        .secondline{
            display: flex;

        }
        .secondline label{
            margin-top: 0.7rem;
            margin-right: 0.5rem;
        }
        .secondline input{
            margin-right: 0.5rem;
        }

        .btn-container {
            display: flex;
            justify-content: center; /* Center the button within the container */
        }

        .btn{
            padding: 10px 15px;
            font-size: 16px;
            background-color: #2196f3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }  
        
        .image{
            margin-top: -10rem;
        }
    </style>

</head>
<body>
    <div class='header'>
        <a href="EMP_Maintenance.php" class="back float-end"><i class="fa-solid fa-backward"></i></a>
        <h3>EMPLOYEE DETAILS</h3>
        <div class='logout_btn'><i class="fa-solid fa-right-from-bracket"></i></div>
    </div>
    
    <div class='container'>
        <div class='main'>
            <div class='table-container'>
                <div class='action-bar'>
                    <div class="container">
                        <div class="welcome-container">
                        <?php
                            if (isset($_GET['IDNumber'])) {
                                $emp_id = mysqli_real_escape_string($con, $_GET['IDNumber']);
                                $query = "SELECT * FROM employeeinformation WHERE IDNumber='$emp_id' ";
                                $query_run = mysqli_query($con, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    $employee = mysqli_fetch_array($query_run);
                                    ?>
                                    <form action="db.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="emp_id" value="<?= $employee['IDNumber']; ?>">

                                        <div class="firstline">
                                            <label for="code">Code:</label>
                                            <input type="text" name="code" value="<?= $employee['Code']; ?>" class="form-control" readonly>

                                            <label for="idNumber">ID_Number:</label>
                                            <input type="text" name="id_number" value="<?= $employee['IDNumber']; ?>" class="form-control" readonly>

                                            <div class="image">
                                            <input type="image" name="avatar" value=img src="../Photo/<?= $employee['Avatar']; ?>" alt="Avatar" style="width: 200px; height: 200px;" readonly>
                                            </div>
                                        </div>

                                        <label for="fullName">Full Name:</label>
                                        <input type="text" name="full_name" value="<?= $employee['FullName']; ?>" class="form-control" readonly>

                                        <label for="address">Address:</label>
                                        <input type="text" name="address" value="<?= $employee['Address']; ?>" class="form-control" readonly>

                                        <label for="phoneNumber">Phone Number:</label>
                                        <input type="text" name="phone_number" value="<?= $employee['PhoneNumber']; ?>" class="form-control" readonly>

                                        <label for="email">Email:</label>
                                        <input type="email" name="email" value="<?= $employee['Email']; ?>" class="form-control" readonly>

                                        <div class="secondline">
                                            <label for="sex">Sex:</label>
                                            <input type="text" name="sex" value="<?= $employee['Sex']; ?>" class="form-control" readonly>

                                            <label for="dateOfBirth">Date_of_Birth:</label>
                                            <input type="date" name="date_of_birth" value="<?= $employee['DateOfBirth']; ?>" class="form-control" readonly>
                                        </div>

                                        <label for="placeOfBirth">Place of Birth:</label>
                                        <input type="text" name="place_of_birth" value="<?= $employee['PlaceOfBirth']; ?>" class="form-control" readonly>

                                        <label for="employeePosition">Employee Position:</label>
                                        <input type="text" name="employee_position" value="<?= $employee['EmployeePosition']; ?>" class="form-control" readonly>

                                        <label for="department">Department:</label>
                                        <input type="text" name="department" value="<?= $employee['Department']; ?>" class="form-control" readonly>

                                        <label for="dateOfHire">Date of Hire:</label>
                                        <input type="date" name="date_of_hire" value="<?= $employee['DateOfHire']; ?>" class="form-control" readonly>

                                        <label for="employeeStatus">Employee Status:</label>
                                        <input type="text" name="employee_status" value="<?= $employee['EmployeeStatus']; ?>" class="form-control" readonly>
                                    </form>
                                    <?php
                                } else {
                                    echo "<h4>No Such IDNumber Found</h4>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
    <script>
        function logout() {
                window.location.href = '../ATLOG/admin_logout.php';
            }

            // Attach the logout function to the click event of the logout_btn
            document.querySelector('.logout_btn').addEventListener('click', logout);
    </script>
</body>
</html>
