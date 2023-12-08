<?php

$host = "localhost";
$user = "root";
$password = "";
$databasename = "eams";

$con = mysqli_connect($host, $user, $password, $databasename);


if (isset($_POST['delete_emp'])) {
    // Assuming the password input field is named 'password'
    $entered_password = mysqli_real_escape_string($con, $_POST['password']);
    $emp_id = mysqli_real_escape_string($con, $_POST['employee_id']); // Corrected the name here

    // Check if the entered password is equal to 'Penny_2023'
    if ($entered_password == 'Penny2023') {
        // Proceed with the deletion
        $query = "DELETE FROM employeeinformation WHERE IDNumber='$emp_id' ";
        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            echo '<script>alert("Employee Data Deleted Successfully!");</script>';
            echo '<script>window.location.href = "EMP_Maintenance.php";</script>';
            exit(0);
        } else {
            echo '<script>alert("Employee Data Not Deleted!");</script>';
            echo '<script>window.location.href = "EMP_Maintenance.php";</script>';
            exit(0);
        }
    } else {
        // Incorrect password
        echo '<script>alert("Incorrect Password!");</script>';
        echo '<script>window.location.href = "EMP_Maintenance.php";</script>';
        exit(0);
    }
}



error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['update_emp'])) {
    $IDNumber = mysqli_real_escape_string($con, $_POST['emp_id']);
    $code = mysqli_real_escape_string($con, $_POST['code']);

    // Handling file upload for the avatar
    $avatar = '';

    if ($_FILES['avatar']['name']) {
        $avatar_tmp = $_FILES['avatar']['tmp_name'];

        // Specify the desired destination folder
        $destination_folder = "../Photo/";

        // Specify the desired file name (you can adjust this if needed)
        $avatar = "user_uploaded_" . time() . "_" . basename($_FILES['avatar']['name']);

        // Move the file to the destination folder
        if (move_uploaded_file($avatar_tmp, $destination_folder . $avatar)) {
            // File was successfully moved
        } else {
            // Error moving the file
            echo '<script type="text/javascript">alert("Error uploading the photo.");</script>';
            exit; // Stop execution if there's an error
        }
    }

    // Check if a new file is uploaded before updating $avatar in the SQL query
    if ($avatar == '') {
        // If no new file is uploaded, fetch the existing avatar from the database
        $select_query = "SELECT Avatar FROM employeeinformation WHERE IDNumber = '$IDNumber'";
        $select_result = mysqli_query($con, $select_query);

        if ($select_result && mysqli_num_rows($select_result) > 0) {
            $row = mysqli_fetch_assoc($select_result);
            $avatar = $row['Avatar'];
        }
    }

    $fullName = mysqli_real_escape_string($con, $_POST['full_name']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $phoneNumber = mysqli_real_escape_string($con, $_POST['phone_number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
    $dateOfBirth = mysqli_real_escape_string($con, $_POST['date_of_birth']);
    $placeOfBirth = mysqli_real_escape_string($con, $_POST['place_of_birth']);
    $idNumber = mysqli_real_escape_string($con, $_POST['id_number']);
    $employeePosition = mysqli_real_escape_string($con, $_POST['employee_position']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $dateOfHire = mysqli_real_escape_string($con, $_POST['date_of_hire']);
    $employeeStatus = mysqli_real_escape_string($con, $_POST['employee_status']);

    // Update the query to match the column names in the database
    $query = "UPDATE employeeinformation SET
        Code = '$code',
        Avatar = '$avatar',
        FullName = '$fullName',
        Address = '$address',
        PhoneNumber = '$phoneNumber',
        Email = '$email',
        Sex = '$sex',
        DateOfBirth = '$dateOfBirth',
        PlaceOfBirth = '$placeOfBirth',
        IDNumber = '$idNumber',
        EmployeePosition = '$employeePosition',
        Department = '$department',
        DateOfHire = '$dateOfHire',
        EmployeeStatus = '$employeeStatus'
        WHERE IDNumber = '$IDNumber'";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        echo '<script type="text/javascript">alert("Employee information updated successfully."); window.location.href = "EMP_Maintenance.php"; </script>';
    } else {
        echo '<script type="text/javascript">alert("Error updating employee information.");</script>';
    }
}
?>
