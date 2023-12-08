<?php
// Connect to your MySQL database (replace placeholders with your actual values)
$conn = mysqli_connect('localhost', 'root', '', 'eams');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"];
    $fullName = $_POST["fullName"];
    $address = $_POST["address"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $sex = $_POST["sex"];
    $dateOfBirth = $_POST["dateOfBirth"];
    $placeOfBirth = $_POST["placeOfBirth"];
    $idNumber = $_POST["idNumber"];
    $employeePosition = $_POST["employeePosition"];
    $department = $_POST["department"];
    $dateOfHire = $_POST["dateOfHire"];
    $employeeStatus = $_POST["employeeStatus"];

    // Handle file upload (avatar)
    $avatar = $_FILES["avatar"]["name"];
    $avatar_tmp = $_FILES["avatar"]["tmp_name"];
    move_uploaded_file($avatar_tmp, "Photo/" . $avatar);

    // Insert data into EmployeeInformation table
    $sql = "INSERT INTO EmployeeInformation (
        Code, Avatar, FullName, Address, PhoneNumber, Email, Sex, DateOfBirth,
        PlaceOfBirth, IDNumber, EmployeePosition, Department, DateOfHire, EmployeeStatus
    ) VALUES (
        '$code', '$avatar', '$fullName', '$address', '$phoneNumber', '$email', '$sex', '$dateOfBirth',
        '$placeOfBirth', '$idNumber', '$employeePosition', '$department', '$dateOfHire', '$employeeStatus'
    )";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Employee information added successfully.");';
        echo 'window.location.href="./ADMIN/EMP_Maintenance.php";</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>